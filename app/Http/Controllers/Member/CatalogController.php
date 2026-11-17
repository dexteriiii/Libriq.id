<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Setting;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        $books = $query->orderBy('title')->paginate(15)->withQueryString();

        $categories = Book::query()
            ->select('category')
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('member.catalog.index', [
            'books' => $books,
            'categories' => $categories,
            'search' => $search,
            'activeCategory' => $category,
        ]);
    }

    public function show(Book $book)
    {
        $user = auth()->user();

        // Cek apakah user sudah punya permintaan aktif untuk buku ini
        $alreadyRequested = $user->loans()
            ->where('book_id', $book->id)
            ->whereIn('status', ['pending', 'borrowed', 'overdue'])
            ->first();

        // Cek kuota pinjaman aktif
        $maxActiveLoans = (int) Setting::get('max_active_loans', 0);
        $activeLoansCount = $user->loans()
            ->whereIn('status', ['pending', 'borrowed', 'overdue'])
            ->count();
        $isLoanLimitReached = ($maxActiveLoans > 0 && $activeLoansCount >= $maxActiveLoans);

        return view('member.catalog.show', [
            'book'               => $book,
            'alreadyRequested'   => $alreadyRequested,
            'maxActiveLoans'     => $maxActiveLoans,
            'activeLoansCount'   => $activeLoansCount,
            'isLoanLimitReached' => $isLoanLimitReached,
        ]);
    }
}
