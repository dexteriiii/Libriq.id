<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InsightController extends Controller
{
    public function index(Request $request)
    {
        // ── 1. Buku Paling Sering Dipinjam ──────────────────────────────────
        $topBooks = Book::withCount('loans')
            ->orderByDesc('loans_count')
            ->take(10)
            ->get();

        // ── 2. Kategori Favorit ──────────────────────────────────────────────
        $topCategories = DB::table('loans')
            ->join('books', 'loans.book_id', '=', 'books.id')
            ->select('books.category', DB::raw('count(loans.id) as total_loans'))
            ->whereNotNull('books.category')
            ->where('books.category', '!=', '')
            ->groupBy('books.category')
            ->orderByDesc('total_loans')
            ->get();

        $totalCategorizedLoans = $topCategories->sum('total_loans') ?: 1;

        // ── 3. Tren Peminjaman per Bulan (6 Bulan Terakhir) ───────────────────
        $monthlyLabels = [];
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthStart = Carbon::now()->subMonths($i)->startOfMonth();
            $monthEnd   = Carbon::now()->subMonths($i)->endOfMonth();
            $monthlyLabels[] = $monthStart->translatedFormat('M Y');
            $monthlyData[]   = Loan::whereBetween('created_at', [$monthStart, $monthEnd])->count();
        }

        // ── 4. Tren Peminjaman per Minggu (8 Minggu Terakhir) ─────────────────
        $weeklyLabels = [];
        $weeklyData = [];
        for ($i = 7; $i >= 0; $i--) {
            $weekStart = Carbon::now()->subWeeks($i)->startOfWeek();
            $weekEnd   = Carbon::now()->subWeeks($i)->endOfWeek();
            $weeklyLabels[] = $weekStart->translatedFormat('d M') . ' - ' . $weekEnd->translatedFormat('d M');
            $weeklyData[]   = Loan::whereBetween('created_at', [$weekStart, $weekEnd])->count();
        }

        // ── 5. Status Distribusi Sirkulasi ───────────────────────────────────
        $statusCounts = [
            'borrowed' => Loan::where('status', 'borrowed')->count(),
            'returned' => Loan::where('status', 'returned')->count(),
            'overdue'  => Loan::where('status', 'overdue')->count(),
            'pending'  => Loan::where('status', 'pending')->count(),
        ];

        // ── 6. Rekomendasi Pengadaan Koleksi Baru ────────────────────────────
        $needRestockBooks = Book::where('available_stock', '<=', 1)
            ->withCount('loans')
            ->orderByDesc('loans_count')
            ->take(5)
            ->get();

        $totalBooksCount  = Book::count();
        $totalCopiesCount = (int) Book::sum('total_stock') ?: 1;
        $totalLoansCount  = Loan::count();
        $turnoverRate     = round($totalLoansCount / $totalCopiesCount, 2);

        return view('admin.insights.index', compact(
            'topBooks',
            'topCategories',
            'totalCategorizedLoans',
            'monthlyLabels',
            'monthlyData',
            'weeklyLabels',
            'weeklyData',
            'statusCounts',
            'needRestockBooks',
            'totalBooksCount',
            'totalCopiesCount',
            'totalLoansCount',
            'turnoverRate'
        ));
    }
}
