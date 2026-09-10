<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        // ── Metric cards ──────────────────────────────────────────────────────
        $totalBooks     = Book::count();
        $totalMembers   = User::where('role', 'member')->count();
        $totalBorrowed  = Loan::whereIn('status', ['borrowed', 'overdue'])->count();
        $totalOverdue   = Loan::where('status', 'overdue')->count();
        $totalPending   = Loan::where('status', 'pending')->count();
        $totalUnpaidFine = (int) Loan::whereNull('fine_paid_at')
            ->where('fine_amount', '>', 0)
            ->sum('fine_amount');

        // ── Recent pending requests (max 5) ───────────────────────────────────
        $pendingLoans = Loan::with(['borrower', 'book'])
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        // ── Active overdue loans (max 5) ──────────────────────────────────────
        $overdueLoans = Loan::with(['borrower', 'book'])
            ->where('status', 'overdue')
            ->orderBy('due_date')
            ->limit(5)
            ->get();

        // ── Stock summary ─────────────────────────────────────────────────────
        $outOfStockBooks = Book::where('available_stock', 0)->count();
        $limitedBooks    = Book::where('available_stock', '>', 0)
            ->where('available_stock', '<=', 2)
            ->count();

        return view('admin.dashboard', compact(
            'totalBooks',
            'totalMembers',
            'totalBorrowed',
            'totalOverdue',
            'totalPending',
            'totalUnpaidFine',
            'pendingLoans',
            'overdueLoans',
            'outOfStockBooks',
            'limitedBooks',
        ));
    }
}
