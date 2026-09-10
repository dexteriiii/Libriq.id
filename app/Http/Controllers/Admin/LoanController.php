<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Services\LoanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function __construct(private LoanService $loanService) {}

    // ─── Index: semua transaksi ───────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = Loan::with(['borrower', 'book'])->latest();

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($q = $request->input('q')) {
            $query->where(function ($sq) use ($q) {
                $sq->whereHas('borrower', fn($u) => $u->where('name', 'like', "%{$q}%")
                                                       ->orWhere('email', 'like', "%{$q}%"))
                   ->orWhereHas('book', fn($b) => $b->where('title', 'like', "%{$q}%"))
                   ->orWhere('id', $q);
            });
        }

        $loans = $query->paginate(20)->withQueryString();

        return view('admin.loans.index', compact('loans'));
    }

    // ─── Pending antrean ──────────────────────────────────────────────────────

    public function pending()
    {
        $loans = Loan::with(['borrower', 'book'])
            ->where('status', 'pending')
            ->oldest()
            ->paginate(20);

        return view('admin.loans.pending', compact('loans'));
    }

    // ─── Approve ─────────────────────────────────────────────────────────────

    public function approve(Loan $loan): RedirectResponse
    {
        $this->loanService->approveLoan($loan);

        return back()->with('success', "Peminjaman #{$loan->id} disetujui. Stok berkurang 1 eksemplar.");
    }

    // ─── Reject ───────────────────────────────────────────────────────────────

    public function reject(Request $request, Loan $loan): RedirectResponse
    {
        $request->validate(['reason' => ['nullable', 'string', 'max:255']]);

        $this->loanService->rejectLoan($loan, $request->input('reason'));

        return back()->with('success', "Peminjaman #{$loan->id} ditolak.");
    }

    // ─── Process Return ───────────────────────────────────────────────────────

    public function processReturn(Loan $loan): RedirectResponse
    {
        $finalFine = $this->loanService->processReturn($loan);

        $msg = "Pengembalian buku berhasil dicatat.";
        if ($finalFine > 0) {
            $msg .= ' Denda: Rp' . number_format($finalFine, 0, ',', '.');
        }

        return back()->with('success', $msg);
    }

    // ─── Mark Fine Paid ───────────────────────────────────────────────────────

    public function markFinePaid(Loan $loan): RedirectResponse
    {
        $this->loanService->markFinePaid($loan);

        return back()->with('success', "Denda peminjaman #{$loan->id} telah dicatat sebagai lunas.");
    }

    // ─── Show detail ──────────────────────────────────────────────────────────

    public function show(Loan $loan)
    {
        $loan->load(['borrower', 'book']);

        return view('admin.loans.show', compact('loan'));
    }
}
