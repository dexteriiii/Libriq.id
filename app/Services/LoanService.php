<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\Notification;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LoanService
{
    // -------------------------------------------------------------------------
    // Approve Loan (Pending → Borrowed)
    // -------------------------------------------------------------------------

    /**
     * Setujui permintaan peminjaman.
     * - Set status = borrowed
     * - Set borrow_date & due_date (dari setting loan_duration_days)
     * - Kurangi available_stock dengan locking untuk mencegah race condition
     */
    public function approveLoan(Loan $loan): void
    {
        abort_if($loan->status !== 'pending', 422, 'Hanya peminjaman berstatus pending yang bisa disetujui.');

        $loanDays = (int) Setting::get('loan_duration_days', 7);

        DB::transaction(function () use ($loan, $loanDays) {
            // Lock buku agar tidak ada concurrent approval yang melebihi stok
            $book = \App\Models\Book::whereKey($loan->book_id)->lockForUpdate()->first();

            abort_if($book->available_stock <= 0, 422, 'Stok buku telah habis, tidak dapat disetujui.');

            $book->decrement('available_stock');

            $borrowDate = Carbon::today();
            $dueDate    = $borrowDate->copy()->addDays($loanDays);

            $loan->update([
                'status'      => 'borrowed',
                'borrow_date' => $borrowDate,
                'due_date'    => $dueDate,
            ]);

            // Notifikasi in-app ke borrower
            Notification::create([
                'user_id'    => $loan->borrower_id,
                'type'       => 'loan_approved',
                'message'    => "Permintaan peminjamanmu untuk buku \"{$book->title}\" telah disetujui. Harap dikembalikan sebelum {$dueDate->translatedFormat('d F Y')}.",
                'created_at' => now(),
            ]);
        });
    }

    // -------------------------------------------------------------------------
    // Reject Loan (Pending → Rejected)
    // -------------------------------------------------------------------------

    /**
     * Tolak permintaan peminjaman.
     */
    public function rejectLoan(Loan $loan, ?string $reason = null): void
    {
        abort_if($loan->status !== 'pending', 422, 'Hanya peminjaman berstatus pending yang bisa ditolak.');

        $loan->update(['status' => 'rejected']);

        $message = "Permintaan peminjamanmu untuk buku \"{$loan->book->title}\" ditolak.";
        if ($reason) {
            $message .= " Alasan: {$reason}";
        }

        Notification::create([
            'user_id'    => $loan->borrower_id,
            'type'       => 'loan_rejected',
            'message'    => $message,
            'created_at' => now(),
        ]);
    }

    // -------------------------------------------------------------------------
    // Return Request & Approval (Borrowed / Overdue → Returned)
    // -------------------------------------------------------------------------

    /**
     * Member mengajukan pengembalian buku (menunggu konfirmasi admin).
     */
    public function requestReturn(Loan $loan): void
    {
        abort_if(
            ! in_array($loan->status, ['borrowed', 'overdue']),
            422,
            'Hanya peminjaman aktif yang dapat diajukan pengembaliannya.'
        );
        abort_if(
            $loan->return_status === 'pending',
            422,
            'Pengajuan pengembalian buku ini sudah dikirim dan sedang menunggu konfirmasi admin.'
        );

        $loan->update([
            'return_status'       => 'pending',
            'return_requested_at' => now(),
        ]);

        Notification::create([
            'user_id'    => $loan->borrower_id,
            'type'       => 'return_requested',
            'message'    => "Pengajuan pengembalian buku \"{$loan->book->title}\" telah dikirim. Silakan serahkan buku fisik ke petugas perpustakaan untuk dikonfirmasi.",
            'created_at' => now(),
        ]);
    }

    /**
     * Admin menolak pengajuan pengembalian buku (misal: buku belum diterima fisik / rusak).
     */
    public function rejectReturn(Loan $loan, ?string $reason = null): void
    {
        abort_if($loan->return_status !== 'pending', 422, 'Tidak ada pengajuan pengembalian yang pending.');

        $loan->update(['return_status' => 'rejected']);

        $msg = "Pengajuan pengembalian buku \"{$loan->book->title}\" ditolak oleh Admin.";
        if ($reason) {
            $msg .= " Alasan: {$reason}";
        }

        Notification::create([
            'user_id'    => $loan->borrower_id,
            'type'       => 'return_rejected',
            'message'    => $msg,
            'created_at' => now(),
        ]);
    }

    /**
     * Admin menyetujui/memproses pengembalian buku.
     * - Hitung denda final (dikunci di sini)
     * - Set status = returned, return_date = today, return_status = approved
     * - Tambah available_stock
     */
    public function processReturn(Loan $loan): int
    {
        abort_if(
            ! in_array($loan->status, ['borrowed', 'overdue']),
            422,
            'Hanya peminjaman aktif yang dapat diproses pengembaliannya.'
        );

        $finalFine = 0;

        DB::transaction(function () use ($loan, &$finalFine) {
            $book = \App\Models\Book::whereKey($loan->book_id)->lockForUpdate()->first();

            $finalFine = $this->calculateFine($loan);

            $loan->update([
                'status'        => 'returned',
                'return_status' => 'approved',
                'return_date'   => Carbon::today(),
                'fine_amount'   => $finalFine,
            ]);

            $book->increment('available_stock');

            // Notifikasi ke borrower
            $msg = "Pengembalian buku \"{$book->title}\" telah dikonfirmasi oleh Admin.";
            if ($finalFine > 0) {
                $msg .= ' Denda keterlambatan: Rp' . number_format($finalFine, 0, ',', '.') . '. Harap bayar denda di kasir admin perpustakaan.';
            }

            Notification::create([
                'user_id'    => $loan->borrower_id,
                'type'       => 'book_returned',
                'message'    => $msg,
                'created_at' => now(),
            ]);
        });

        return $finalFine;
    }

    // -------------------------------------------------------------------------
    // Fine Payment Request & Confirmation
    // -------------------------------------------------------------------------

    /**
     * Member mengajukan konfirmasi pembayaran denda cash ke admin.
     */
    public function requestFinePayment(Loan $loan): void
    {
        $currentFine = $loan->calculateCurrentFine();
        abort_if($currentFine <= 0, 422, 'Tidak ada denda pada transaksi ini.');
        abort_if(! is_null($loan->fine_paid_at), 422, 'Denda sudah lunas sebelumnya.');
        abort_if(
            $loan->fine_payment_status === 'pending',
            422,
            'Konfirmasi pembayaran denda sedang menunggu verifikasi admin.'
        );

        $loan->update([
            'fine_payment_status'       => 'pending',
            'fine_payment_requested_at' => now(),
        ]);

        Notification::create([
            'user_id'    => $loan->borrower_id,
            'type'       => 'fine_payment_requested',
            'message'    => "Pengajuan konfirmasi pembayaran denda buku \"{$loan->book->title}\" sebesar Rp" . number_format($currentFine, 0, ',', '.') . " telah dikirim ke Admin.",
            'created_at' => now(),
        ]);
    }

    /**
     * Admin mengonfirmasi pembayaran denda lunas.
     */
    public function markFinePaid(Loan $loan): void
    {
        abort_if($loan->fine_amount <= 0 && $loan->calculateCurrentFine() <= 0, 422, 'Tidak ada denda pada transaksi ini.');
        abort_if(! is_null($loan->fine_paid_at), 422, 'Denda sudah lunas sebelumnya.');

        // Jika buku belum berstatus returned, proses return sekaligus atau kunci denda
        $finalFine = (int) $loan->fine_amount > 0 ? $loan->fine_amount : $loan->calculateCurrentFine();

        $loan->update([
            'fine_amount'         => $finalFine,
            'fine_paid_at'        => now(),
            'fine_payment_status' => 'paid',
        ]);

        Notification::create([
            'user_id'    => $loan->borrower_id,
            'type'       => 'fine_paid',
            'message'    => "Pembayaran denda sebesar Rp" . number_format($finalFine, 0, ',', '.') . " untuk buku \"{$loan->book->title}\" telah dikonfirmasi LUNAS oleh Admin.",
            'created_at' => now(),
        ]);
    }

    /**
     * Admin menolak pengajuan konfirmasi pembayaran denda (misal: uang belum diterima / nominal kurang).
     */
    public function rejectFinePayment(Loan $loan, ?string $reason = null): void
    {
        abort_if($loan->fine_payment_status !== 'pending', 422, 'Tidak ada pengajuan verifikasi denda yang pending.');

        $loan->update(['fine_payment_status' => 'rejected']);

        $msg = "Pengajuan verifikasi pembayaran denda buku \"{$loan->book->title}\" ditolak oleh Admin (uang belum diterima).";
        if ($reason) {
            $msg .= " Alasan: {$reason}";
        }

        Notification::create([
            'user_id'    => $loan->borrower_id,
            'type'       => 'fine_payment_rejected',
            'message'    => $msg,
            'created_at' => now(),
        ]);
    }

    // -------------------------------------------------------------------------
    // Fine Calculation
    // -------------------------------------------------------------------------

    /**
     * Hitung denda berdasarkan hari keterlambatan × tarif per hari.
     * Mengembalikan 0 jika tidak terlambat.
     */
    public function calculateFine(Loan $loan): int
    {
        if (! $loan->due_date) {
            return 0;
        }

        $today = Carbon::today();

        if (! $today->gt($loan->due_date)) {
            return 0;
        }

        $daysLate  = (int) abs($today->diffInDays($loan->due_date));
        $finePerDay = (int) Setting::get('fine_per_day', 2000);

        return $daysLate * $finePerDay;
    }

    // -------------------------------------------------------------------------
    // Renewal (Perpanjangan Peminjaman)
    // -------------------------------------------------------------------------

    /**
     * Setujui perpanjangan peminjaman.
     * - Tambah due_date sesuai setting loan_duration_days
     * - Set renewal_status = approved & increment renewal_count
     */
    public function approveRenewal(Loan $loan): Carbon
    {
        abort_if($loan->status !== 'borrowed', 422, 'Hanya peminjaman aktif yang bisa diperpanjang.');
        abort_if($loan->renewal_status !== 'pending', 422, 'Tidak ada pengajuan perpanjangan yang pending.');

        $loanDays = (int) Setting::get('loan_duration_days', 7);
        $baseDate = ($loan->due_date && $loan->due_date->isFuture()) ? $loan->due_date : Carbon::today();
        $newDueDate = $baseDate->copy()->addDays($loanDays);

        $loan->update([
            'due_date'        => $newDueDate,
            'renewal_status'  => 'approved',
            'renewal_count'   => $loan->renewal_count + 1,
        ]);

        // Notifikasi ke borrower
        Notification::create([
            'user_id'    => $loan->borrower_id,
            'type'       => 'loan_renewed',
            'message'    => "Perpanjangan peminjaman buku \"{$loan->book->title}\" disetujui! Batas pengembalian baru: {$newDueDate->translatedFormat('d F Y')}.",
            'created_at' => now(),
        ]);

        return $newDueDate;
    }

    /**
     * Tolak perpanjangan peminjaman.
     */
    public function rejectRenewal(Loan $loan, ?string $reason = null): void
    {
        abort_if($loan->renewal_status !== 'pending', 422, 'Tidak ada pengajuan perpanjangan yang pending.');

        $loan->update(['renewal_status' => 'rejected']);

        $message = "Permintaan perpanjangan peminjaman buku \"{$loan->book->title}\" ditolak.";
        if ($reason) {
            $message .= " Alasan: {$reason}";
        }

        Notification::create([
            'user_id'    => $loan->borrower_id,
            'type'       => 'renewal_rejected',
            'message'    => $message,
            'created_at' => now(),
        ]);
    }
}

