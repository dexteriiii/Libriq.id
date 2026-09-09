<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
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

        $maxActiveLoans = (int) (DB::table('settings')->where('key', 'max_active_loans')->value('value') ?? 3);
        $maxUnpaidFine  = (int) (DB::table('settings')->where('key', 'max_unpaid_fine')->value('value') ?? 20000);

        $activeLoanCount = $user->loans()
            ->whereIn('status', ['pending', 'borrowed', 'overdue'])
            ->count();

        $unpaidFine = $user->loans()->whereNull('fine_paid_at')->sum('fine_amount');

        if ($unpaidFine >= $maxUnpaidFine) {
            return back()->with('error', 'Kamu memiliki denda belum lunas sebesar Rp'
                . number_format($unpaidFine, 0, ',', '.')
                . '. Selesaikan pembayaran denda sebelum meminjam buku baru.');
        }

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
}
