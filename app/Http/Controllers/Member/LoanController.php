<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Notification;
use App\Models\User;
use App\Services\LoanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    /** Riwayat & pinjaman aktif milik member yang sedang login */
    public function history(Request $request)
    {
        $loans = Auth::user()->loans()
            ->with('book')
            ->latest('created_at')
            ->paginate(10);

        return view('member.loans.history', [
            'loans' => $loans,
        ]);
    }

    /**
     * Ajukan peminjaman (hold request).
     * Sesuai PRD 4.4: validasi kuota maksimum & denda aktif sebelum status "Pending Request".
     */
    public function store(Request $request, Book $book): RedirectResponse
    {
        $user = Auth::user();

        // Validasi aturan: Jika punya denda belum lunas atau buku terlambat, wajib lunas & kembali dulu
        if (! $user->canBorrowBooks()) {
            return back()->with('error', $user->getBorrowBlockReason());
        }

        $maxActiveLoans = (int) (DB::table('settings')->where('key', 'max_active_loans')->value('value') ?? 3);

        $activeLoanCount = $user->loans()
            ->whereIn('status', ['pending', 'borrowed', 'overdue'])
            ->count();

        if ($activeLoanCount >= $maxActiveLoans) {
            return back()->with('error', "Kamu sudah mencapai batas maksimum {$maxActiveLoans} peminjaman aktif.");
        }

        if ($book->available_stock <= 0) {
            return back()->with('error', 'Stok buku ini sedang tidak tersedia.');
        }

        $alreadyRequested = $user->loans()
            ->where('book_id', $book->id)
            ->whereIn('status', ['pending', 'borrowed', 'overdue'])
            ->exists();

        if ($alreadyRequested) {
            return back()->with('error', 'Kamu sudah memiliki permintaan aktif untuk buku ini.');
        }

        DB::transaction(function () use ($user, $book) {
            // Lock baris buku untuk mencegah race condition antar member (PRD 4.2)
            $lockedBook = Book::whereKey($book->id)->lockForUpdate()->first();

            if ($lockedBook->available_stock <= 0) {
                abort(422, 'Stok buku baru saja habis.');
            }

            Loan::create([
                'borrower_id' => $user->id,
                'book_id' => $lockedBook->id,
                'status' => 'pending',
            ]);

            // Kirim notifikasi ke semua admin perpustakaan
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'loan_requested',
                    'message' => "Permintaan Peminjaman: {$user->name} mengajukan peminjaman buku \"{$lockedBook->title}\".",
                    'created_at' => now(),
                ]);
            }
        });

        return redirect()
            ->route('member.loans.history')
            ->with('success', 'Permintaan peminjaman berhasil diajukan. Menunggu persetujuan admin.');
    }

    /** Member membatalkan permintaan yang masih berstatus pending */
    public function cancel(Loan $loan): RedirectResponse
    {
        abort_unless($loan->borrower_id === Auth::id(), 403);
        abort_unless($loan->status === 'pending', 422, 'Permintaan ini sudah diproses dan tidak dapat dibatalkan.');

        $loan->delete();

        return back()->with('success', 'Permintaan peminjaman dibatalkan.');
    }

    /** Member mengajukan pengembalian buku (menunggu konfirmasi admin) */
    public function returnBook(Loan $loan, LoanService $loanService): RedirectResponse
    {
        abort_unless($loan->borrower_id === Auth::id(), 403);
        abort_unless(in_array($loan->status, ['borrowed', 'overdue']), 422, 'Hanya buku yang sedang dipinjam atau overdue yang bisa dikembalikan.');

        if ($loan->return_status === 'pending') {
            return back()->with('error', 'Pengajuan pengembalian sudah dikirim dan sedang menunggu konfirmasi admin.');
        }

        $loanService->requestReturn($loan);

        // Notifikasi ke semua admin
        $user = Auth::user();
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            \App\Models\Notification::create([
                'user_id'    => $admin->id,
                'type'       => 'return_requested',
                'message'    => "Pengajuan Pengembalian: {$user->name} mengajukan pengembalian buku \"{$loan->book->title}\".",
                'created_at' => now(),
            ]);
        }

        return back()->with('success', 'Pengajuan pengembalian buku berhasil dikirim! Silakan serahkan buku fisik ke admin perpustakaan untuk dikonfirmasi.');
    }

    /** Member mengajukan konfirmasi pembayaran denda cash ke admin */
    public function payFine(Loan $loan, LoanService $loanService): RedirectResponse
    {
        abort_unless($loan->borrower_id === Auth::id(), 403);

        $currentFine = $loan->calculateCurrentFine();
        if ($currentFine <= 0 || ! is_null($loan->fine_paid_at)) {
            return back()->with('error', 'Tidak ada denda aktif yang perlu dibayar pada transaksi ini.');
        }

        if ($loan->fine_payment_status === 'pending') {
            return back()->with('error', 'Pengajuan konfirmasi pembayaran denda sedang menunggu verifikasi admin.');
        }

        $loanService->requestFinePayment($loan);

        // Notifikasi ke semua admin
        $user = Auth::user();
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            \App\Models\Notification::create([
                'user_id'    => $admin->id,
                'type'       => 'fine_payment_requested',
                'message'    => "Konfirmasi Pembayaran Denda: {$user->name} mengajukan verifikasi pembayaran denda Rp" . number_format($currentFine, 0, ',', '.') . " untuk buku \"{$loan->book->title}\".",
                'created_at' => now(),
            ]);
        }

        return back()->with('success', 'Pengajuan konfirmasi pembayaran denda sebesar Rp' . number_format($currentFine, 0, ',', '.') . ' berhasil dikirim! Petugas admin akan memverifikasi.');
    }

    /** Member mengajukan perpanjangan peminjaman buku */
    public function renew(Loan $loan): RedirectResponse
    {
        abort_unless($loan->borrower_id === Auth::id(), 403);

        if ($loan->status !== 'borrowed') {
            return back()->with('error', 'Hanya buku yang sedang aktif dipinjam yang dapat diperpanjang.');
        }

        if ($loan->isOverdue()) {
            return back()->with('error', 'Buku sudah melewati batas jatuh tempo (terlambat) dan tidak dapat diperpanjang. Silakan lakukan pengembalian.');
        }

        if ($loan->hasPendingReservation()) {
            return back()->with('error', 'Buku tidak dapat diperpanjang karena sedang ada antrean reservasi dari anggota lain.');
        }

        if ($loan->renewal_status === 'pending') {
            return back()->with('error', 'Pengajuan perpanjangan buku ini sudah dikirim dan sedang menunggu persetujuan admin.');
        }

        $user = Auth::user();

        $loan->update([
            'renewal_status'       => 'pending',
            'renewal_requested_at' => now(),
        ]);

        // Notifikasi ke semua admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id'    => $admin->id,
                'type'       => 'renewal_requested',
                'message'    => "Pengajuan Perpanjangan: {$user->name} mengajukan perpanjangan buku \"{$loan->book->title}\".",
                'created_at' => now(),
            ]);
        }

        return back()->with('success', 'Permintaan perpanjangan buku berhasil diajukan. Menunggu persetujuan admin.');
    }
}
