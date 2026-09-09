<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Book;
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

        $books = $query->orderBy('title')->paginate(12)->withQueryString();

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
        return view('member.catalog.show', [
            'book' => $book,
        ]);
    }
}
