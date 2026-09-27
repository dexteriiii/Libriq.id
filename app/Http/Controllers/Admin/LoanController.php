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

    // ─── Pending antrean & renewals ───────────────────────────────────────────

    public function pending()
    {
        $loans = Loan::with(['borrower', 'book'])
            ->where('status', 'pending')
            ->oldest()
            ->paginate(20);

        $renewals = Loan::with(['borrower', 'book'])
            ->where('status', 'borrowed')
            ->where('renewal_status', 'pending')
            ->latest('renewal_requested_at')
            ->get();

        $returns = Loan::with(['borrower', 'book'])
            ->whereIn('status', ['borrowed', 'overdue'])
            ->where('return_status', 'pending')
            ->latest('return_requested_at')
            ->get();

        $finePayments = Loan::with(['borrower', 'book'])
            ->where('fine_payment_status', 'pending')
            ->whereNull('fine_paid_at')
            ->latest('fine_payment_requested_at')
            ->get();

        return view('admin.loans.pending', compact('loans', 'renewals', 'returns', 'finePayments'));
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

    // ─── Renewal Approve ──────────────────────────────────────────────────────

    public function approveRenewal(Loan $loan): RedirectResponse
    {
        $newDueDate = $this->loanService->approveRenewal($loan);

        return back()->with('success', "Perpanjangan peminjaman #{$loan->id} disetujui hingga {$newDueDate->translatedFormat('d F Y')}.");
    }

    // ─── Renewal Reject ───────────────────────────────────────────────────────

    public function rejectRenewal(Request $request, Loan $loan): RedirectResponse
    {
        $request->validate(['reason' => ['nullable', 'string', 'max:255']]);

        $this->loanService->rejectRenewal($loan, $request->input('reason'));

        return back()->with('success', "Pengajuan perpanjangan peminjaman #{$loan->id} ditolak.");
    }

    // ─── Process Return ───────────────────────────────────────────────────────

    public function processReturn(Loan $loan): RedirectResponse
    {
        $finalFine = $this->loanService->processReturn($loan);

        $msg = "Pengembalian buku #{$loan->id} berhasil dikonfirmasi.";
        if ($finalFine > 0) {
            $msg .= ' Denda keterlambatan terkunci: Rp' . number_format($finalFine, 0, ',', '.');
        }

        return back()->with('success', $msg);
    }

    // ─── Reject Return ─────────────────────────────────────────────────────────

    public function rejectReturn(Request $request, Loan $loan): RedirectResponse
    {
        $request->validate(['reason' => ['nullable', 'string', 'max:255']]);

        $this->loanService->rejectReturn($loan, $request->input('reason'));

        return back()->with('success', "Pengajuan pengembalian buku #{$loan->id} telah ditolak.");
    }

    // ─── Mark Fine Paid ───────────────────────────────────────────────────────

    public function markFinePaid(Loan $loan): RedirectResponse
    {
        $this->loanService->markFinePaid($loan);

        return back()->with('success', "Pembayaran denda peminjaman #{$loan->id} telah dikonfirmasi LUNAS.");
    }

    // ─── Reject Fine Payment ───────────────────────────────────────────────────

    public function rejectFinePayment(Request $request, Loan $loan): RedirectResponse
    {
        $request->validate(['reason' => ['nullable', 'string', 'max:255']]);

        $this->loanService->rejectFinePayment($loan, $request->input('reason'));

        return back()->with('success', "Pengajuan verifikasi pembayaran denda peminjaman #{$loan->id} telah ditolak.");
    }

    // ─── Show detail ──────────────────────────────────────────────────────────

    public function show(Loan $loan)
    {
        $loan->load(['borrower', 'book']);

        return view('admin.loans.show', compact('loan'));
    }
}
