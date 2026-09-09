<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        $activeLoans = $user->loans()
            ->with('book')
            ->whereIn('status', ['pending', 'borrowed', 'overdue'])
            ->orderBy('due_date')
            ->get();

        $recentHistory = $user->loans()
            ->with('book')
            ->where('status', 'returned')
            ->latest('return_date')
            ->take(5)
            ->get();

        $unpaidFine = $user->loans()->whereNull('fine_paid_at')->sum('fine_amount');

        $stats = [
            'active_count'   => $activeLoans->count(),
            'overdue_count'  => $activeLoans->where('status', 'overdue')->count(),
            'due_soon_count' => $activeLoans->filter(function (Loan $loan) {
                return $loan->status === 'borrowed'
                    && $loan->due_date
                    && now()->diffInDays($loan->due_date, false) <= 3
                    && now()->diffInDays($loan->due_date, false) >= 0;
            })->count(),
            'unpaid_fine' => $unpaidFine,
        ];

        return view('member.dashboard', [
            'stats' => $stats,
            'activeLoans' => $activeLoans,
            'recentHistory' => $recentHistory,
        ]);
    }
}
