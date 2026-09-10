<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBookRequest;
use App\Http\Requests\Admin\UpdateBookRequest;
use App\Models\Book;
use App\Services\GoogleBooksService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function __construct(private GoogleBooksService $googleBooks) {}

    // ─── Listing ─────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = Book::query();

        if ($q = $request->input('q')) {
            $query->where(function ($sq) use ($q) {
                $sq->where('title', 'like', "%{$q}%")
                   ->orWhere('author', 'like', "%{$q}%")
                   ->orWhere('isbn', 'like', "%{$q}%");
            });
        }

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        if ($stock = $request->input('stock')) {
            match ($stock) {
                'out'     => $query->where('available_stock', 0),
                'limited' => $query->where('available_stock', '>', 0)->where('available_stock', '<=', 2),
                'ok'      => $query->where('available_stock', '>', 2),
                default   => null,
            };
        }

        $books = $query->orderBy('title')->paginate(20)->withQueryString();

        $categories = Book::select('category')
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('admin.books.index', compact('books', 'categories'));
    }

    // ─── Create form ──────────────────────────────────────────────────────────

    public function create()
    {
        return view('admin.books.create');
    }

    // ─── Fetch from Google Books API (Ajax) ───────────────────────────────────

    /**
     * Endpoint JSON yang dipanggil Alpine.js untuk mengisi staging form.
     * PRD 4.1: menampilkan indikator loading, hasil belum tersimpan ke DB.
     */
    public function fetchFromApi(Request $request): JsonResponse
    {
        $request->validate([
            'isbn'  => ['nullable', 'string', 'max:20'],
            'title' => ['nullable', 'string', 'max:255'],
        ]);

        $isbn  = trim($request->input('isbn', ''));
        $title = trim($request->input('title', ''));

        if (! $isbn && ! $title) {
            return response()->json(['error' => 'Masukkan ISBN atau judul buku.'], 422);
        }

        // Cek apakah ISBN sudah ada di database lokal (PRD 4.1 edge case)
        if ($isbn && $existing = Book::where('isbn', $isbn)->first()) {
            return response()->json([
                'warning'  => "ISBN {$isbn} sudah terdaftar di katalog sebagai \"{$existing->title}\".",
                'existing' => ['id' => $existing->id, 'title' => $existing->title],
            ], 409);
        }

        try {
            $data = $isbn
                ? $this->googleBooks->fetchByIsbn($isbn)
                : $this->googleBooks->fetchByTitle($title);
        } catch (\Exception $e) {
            if ($e->getMessage() === 'RATE_LIMIT_EXCEEDED') {
                return response()->json([
                    'error' => 'Google Books API membatasi akses tanpa API Key (Error 429 Rate Limit). Silakan tambahkan GOOGLE_BOOKS_API_KEY di file .env atau input data secara manual.',
                ], 429);
            }
            return response()->json([
                'error' => 'Gagal menghubungi Google Books API. Silakan input manual.',
            ], 500);
        }

        if (! $data) {
            $searchQuery = $isbn ?: $title;
            return response()->json([
                'error' => "Data buku untuk \"{$searchQuery}\" tidak ditemukan di Google Books. Silakan input manual.",
            ], 404);
        }

        return response()->json(['data' => $data]);
    }

    // ─── Store ────────────────────────────────────────────────────────────────

    public function store(StoreBookRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Upload cover jika ada
        if ($request->hasFile('cover')) {
            $validated['cover_path'] = $request->file('cover')->store('covers', 'public');
        }

        // available_stock = total_stock saat pertama kali dibuat
        $validated['available_stock'] = $validated['total_stock'];

        unset($validated['cover']); // hilangkan field file, bukan kolom DB
        Book::create($validated);

        return redirect()
            ->route('admin.books.index')
            ->with('success', "Buku \"{$validated['title']}\" berhasil ditambahkan.");
    }

    // ─── Edit ─────────────────────────────────────────────────────────────────

    public function edit(Book $book)
    {
        return view('admin.books.edit', compact('book'));
    }

    // ─── Update ───────────────────────────────────────────────────────────────

    public function update(UpdateBookRequest $request, Book $book): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('cover')) {
            // Hapus file lama jika ada
            if ($book->cover_path) {
                Storage::disk('public')->delete($book->cover_path);
            }
            $validated['cover_path'] = $request->file('cover')->store('covers', 'public');
        }

        // Sinkronkan available_stock jika total_stock berubah
        $stockDelta = $validated['total_stock'] - $book->total_stock;
        if ($stockDelta !== 0) {
            $validated['available_stock'] = max(0, $book->available_stock + $stockDelta);
        }

        unset($validated['cover']);
        $book->update($validated);

        return redirect()
            ->route('admin.books.index')
            ->with('success', "Data buku \"{$book->title}\" berhasil diperbarui.");
    }

    // ─── Add Stock ────────────────────────────────────────────────────────────

    public function addStock(Request $request, Book $book): RedirectResponse
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:1000'],
        ]);

        $qty = (int) $request->input('quantity');

        $book->increment('total_stock', $qty);
        $book->increment('available_stock', $qty);

        return back()->with('success', "Stok buku \"{$book->title}\" berhasil ditambah {$qty} eksemplar.");
    }

    // ─── Destroy ──────────────────────────────────────────────────────────────

    public function destroy(Book $book): RedirectResponse
    {
        // Tidak bisa dihapus jika masih ada peminjaman aktif
        $hasActiveLoans = $book->loans()
            ->whereIn('status', ['pending', 'borrowed', 'overdue'])
            ->exists();

        if ($hasActiveLoans) {
            return back()->with('error', "Buku \"{$book->title}\" tidak dapat dihapus karena masih ada peminjaman aktif.");
        }

        if ($book->cover_path) {
            Storage::disk('public')->delete($book->cover_path);
        }

        $title = $book->title;
        $book->delete();

        return redirect()
            ->route('admin.books.index')
            ->with('success', "Buku \"{$title}\" berhasil dihapus.");
    }
}
