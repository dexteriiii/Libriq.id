<x-layouts.admin title="Manajemen Katalog — Libriq.id">
    {{-- Page Header --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-heading text-stone-900">Manajemen Katalog</h1>
            <p class="text-stone-500 mt-1">Kelola data buku, stok fisik, dan metadata.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.books.create') }}" class="px-4 py-2 bg-orange-600 text-white rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Buku Baru
            </a>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between">
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm flex items-center justify-between">
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Filter & Search Form --}}
    <div class="mb-6 bg-white p-4 rounded-2xl border border-stone-200 shadow-sm flex flex-col sm:flex-row gap-4 justify-between items-center">
        <form method="GET" action="{{ route('admin.books.index') }}" class="w-full flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Judul, Penulis, atau ISBN..." class="w-full pl-10 pr-4 py-2 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-stone-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <select name="category" class="px-3 py-2 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:outline-none focus:bg-white text-stone-700">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>

            <select name="stock" class="px-3 py-2 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:outline-none focus:bg-white text-stone-700">
                <option value="">Semua Status Stok</option>
                <option value="ok" {{ request('stock') == 'ok' ? 'selected' : '' }}>Tersedia (>2)</option>
                <option value="limited" {{ request('stock') == 'limited' ? 'selected' : '' }}>Terbatas (1-2)</option>
                <option value="out" {{ request('stock') == 'out' ? 'selected' : '' }}>Habis (0)</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-stone-900 text-white text-sm font-medium rounded-xl hover:bg-stone-800 transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['q', 'category', 'stock']))
                <a href="{{ route('admin.books.index') }}" class="px-4 py-2 bg-stone-100 text-stone-600 text-sm font-medium rounded-xl hover:bg-stone-200 transition-colors flex items-center justify-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Books Table --}}
    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-stone-50 border-b border-stone-200 text-stone-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold">Buku & Metadata</th>
                        <th class="px-6 py-4 font-semibold">Kategori / Rak</th>
                        <th class="px-6 py-4 font-semibold text-center">Stok Total</th>
                        <th class="px-6 py-4 font-semibold text-center">Tersedia</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200 text-sm">
                    @forelse ($books as $book)
                        <tr class="hover:bg-stone-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex gap-4 items-center">
                                    <div class="w-12 h-16 bg-stone-100 rounded shadow-sm overflow-hidden shrink-0">
                                        <img src="{{ $book->cover }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-stone-900 line-clamp-1">{{ $book->title }}</h4>
                                        <p class="text-stone-500 text-xs mt-0.5">{{ $book->author ?? 'Penulis tidak diketahui' }} • {{ $book->publish_year ?? '-' }}</p>
                                        <div class="flex items-center gap-2 mt-1.5">
                                            <span class="text-xs font-mono bg-stone-100 text-stone-600 px-1.5 py-0.5 rounded">ISBN: {{ $book->isbn }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-stone-900">{{ $book->category ?? 'Umum' }}</div>
                                <div class="text-stone-500 text-xs mt-0.5 font-mono">Rak: {{ $book->rack_location ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-stone-900 font-medium">{{ $book->total_stock }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($book->available_stock <= 0)
                                    <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full bg-red-100 text-red-700 font-bold text-xs" title="Habis dipinjam">
                                        0 (Habis)
                                    </span>
                                @elseif($book->available_stock <= 2)
                                    <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full bg-amber-100 text-amber-700 font-bold text-xs" title="Terbatas">
                                        {{ $book->available_stock }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700 font-bold text-xs">
                                        {{ $book->available_stock }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.books.edit', $book) }}" class="text-stone-400 hover:text-orange-600 transition-colors p-1" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.536l12.232-12.232z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-stone-500">
                                Belum ada data buku ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if ($books->hasPages())
            <div class="px-6 py-4 border-t border-stone-200 bg-stone-50">
                {{ $books->links() }}
            </div>
        @endif
    </div>
</x-layouts.admin>
