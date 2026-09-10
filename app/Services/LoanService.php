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
    // Process Return (Borrowed / Overdue → Returned)
    // -------------------------------------------------------------------------

    /**
     * Proses pengembalian buku.
     * - Hitung denda final (dikunci di sini)
     * - Set status = returned, return_date = today
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
                'status'      => 'returned',
                'return_date' => Carbon::today(),
                'fine_amount' => $finalFine,
            ]);

            $book->increment('available_stock');

            // Notifikasi ke borrower
            $msg = "Pengembalian buku \"{$book->title}\" telah dicatat.";
            if ($finalFine > 0) {
                $msg .= ' Denda: Rp' . number_format($finalFine, 0, ',', '.') . '.';
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
    // Mark Fine Paid
    // -------------------------------------------------------------------------

    public function markFinePaid(Loan $loan): void
    {
        abort_if($loan->fine_amount <= 0, 422, 'Tidak ada denda pada transaksi ini.');
        abort_if(! is_null($loan->fine_paid_at), 422, 'Denda sudah lunas sebelumnya.');
        abort_if($loan->status !== 'returned', 422, 'Buku belum dikembalikan, denda belum dapat dikunci.');

        $loan->update(['fine_paid_at' => now()]);
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

        $daysLate  = $today->diffInDays($loan->due_date);
        $finePerDay = (int) Setting::get('fine_per_day', 2000);

        return $daysLate * $finePerDay;
    }
}
