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

    // ─── Approve (Cocokkan Kode & Setujui) ────────────────────────────────────

    public function approve(Request $request, Loan $loan): RedirectResponse
    {
        // Periksa apakah borrower punya denda belum lunas atau buku overdue
        if (! $loan->borrower?->canBorrowBooks()) {
            return back()->with('error', "Peminjaman tidak dapat disetujui: " . $loan->borrower?->getBorrowBlockReason());
        }

        // Jika peminjaman memiliki kode verifikasi, cocokkan dengan input admin
        if ($loan->verification_code) {
            $request->validate([
                'verification_code' => ['required', 'string'],
            ], [
                'verification_code.required' => 'Kode verifikasi 5 angka dari anggota wajib dimasukkan.',
            ]);

            if (trim($request->input('verification_code')) !== trim($loan->verification_code)) {
                return back()->with('error', "Kode verifikasi salah! Kode '{$request->input('verification_code')}' tidak cocok dengan bukti peminjaman ini.");
            }
        }

        $this->loanService->approveLoan($loan);

        return back()->with('success', "Peminjaman #{$loan->id} berhasil diverifikasi dan disetujui. Buku dapat diserahkan ke {$loan->borrower?->name}.");
    }

    // ─── Direct Verify by Code (Input Cepat Kode Anggota) ──────────────────────

    public function verifyCode(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
        ], [
            'code.required' => 'Silakan masukkan 5 digit kode verifikasi peminjaman.',
        ]);

        $code = trim($request->input('code'));

        $loan = Loan::with(['borrower', 'book'])
            ->where('status', 'pending')
            ->where('verification_code', $code)
            ->first();

        if (! $loan) {
            return back()->with('error', "Kode verifikasi '{$code}' tidak ditemukan atau status peminjaman sudah tidak pending.");
        }

        // Periksa apakah borrower punya denda belum lunas atau buku overdue
        if (! $loan->borrower?->canBorrowBooks()) {
            return back()->with('error', "Peminjaman untuk {$loan->borrower?->name} tidak dapat disetujui: " . $loan->borrower?->getBorrowBlockReason());
        }

        if ($loan->book?->available_stock <= 0) {
            return back()->with('error', "Stok buku \"{$loan->book?->title}\" saat ini habis, peminjaman tidak dapat disetujui.");
        }

        $this->loanService->approveLoan($loan);

        return back()->with('success', "Verifikasi Berhasil! Kode {$code} cocok untuk {$loan->borrower?->name}. Peminjaman buku \"{$loan->book?->title}\" telah disetujui dan buku siap diserahkan.");
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
