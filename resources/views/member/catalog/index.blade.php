<x-layouts.member title="Katalog Buku — Libriq.id">
    {{-- Header Halaman --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-heading text-2xl sm:text-3xl font-bold text-stone-900">Katalog Buku</h1>
            <p class="text-stone-500 text-sm mt-1">Cari, jelajahi, dan pinjam koleksi buku yang tersedia di perpustakaan.</p>
        </div>
        @if ($search || $activeCategory)
            <a href="{{ route('member.catalog.index') }}" class="self-start sm:self-auto inline-flex items-center gap-1.5 px-3.5 py-2 bg-stone-100 hover:bg-stone-200 text-stone-700 rounded-xl text-xs font-semibold transition-colors shadow-xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Reset Filter
            </a>
        @endif
    </div>

    {{-- Panel Pencarian & Filter --}}
    <div class="bg-white rounded-2xl border border-stone-200 shadow-xs p-3.5 sm:p-4 mb-6">
        <form method="GET" action="{{ route('member.catalog.index') }}" class="flex flex-col sm:flex-row gap-2.5 sm:gap-3">
            <div class="relative flex-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                </svg>
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari judul buku, penulis, atau ISBN..."
                       class="w-full h-11 pl-10 pr-4 rounded-xl bg-stone-50 border border-stone-200 text-sm text-stone-900 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-orange-600 focus:border-orange-600 focus:bg-white transition-all">
            </div>

            <div class="flex items-center gap-2">
                <select name="category" onchange="this.form.submit()"
                        class="h-11 rounded-xl bg-stone-50 border border-stone-200 text-sm text-stone-700 px-3.5 focus:outline-none focus:ring-2 focus:ring-orange-600 focus:border-orange-600 cursor-pointer flex-1 sm:flex-none">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" @selected($activeCategory === $category)>{{ $category }}</option>
                    @endforeach
                </select>

                <button type="submit" class="h-11 bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold px-5 sm:px-6 rounded-xl shadow-xs transition-colors flex items-center justify-center gap-2 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
                    <span>Cari</span>
                </button>
            </div>
        </form>
    </div>

    @if ($books->isEmpty())
        {{-- Empty State --}}
        <div class="bg-white rounded-2xl border border-stone-200 py-16 px-4 text-center shadow-xs">
            <div class="w-14 h-14 bg-stone-100 rounded-full flex items-center justify-center mx-auto mb-3 text-stone-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <h3 class="font-heading font-semibold text-stone-800 text-base">Tidak ada buku ditemukan</h3>
            <p class="text-stone-500 text-sm mt-1 max-w-sm mx-auto">Coba ubah kata kunci pencarian atau pilih kategori lain.</p>
            @if ($search || $activeCategory)
                <a href="{{ route('member.catalog.index') }}" class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 bg-orange-600 text-white rounded-xl text-xs font-semibold hover:bg-orange-700 transition-colors shadow-xs">
                    Tampilkan Semua Buku
                </a>
            @endif
        </div>
    @else
        {{-- Ringkasan jumlah hasil --}}
        <div class="flex items-center justify-between mb-4 px-1">
            <p class="text-xs text-stone-500">
                Menampilkan <span class="font-semibold text-stone-700">{{ $books->firstItem() }}–{{ $books->lastItem() }}</span>
                dari <span class="font-semibold text-stone-700">{{ $books->total() }}</span> judul buku
            </p>
        </div>

        {{-- Grid Katalog --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3.5 sm:gap-5">
            @foreach ($books as $book)
                @php
                    $statusMap = [
                        'available' => [
                            'label' => 'Tersedia',
                            'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
                            'dot'   => 'bg-emerald-500',
                        ],
                        'limited' => [
                            'label' => 'Terbatas',
                            'badge' => 'bg-amber-50 text-amber-700 border-amber-200/80',
                            'dot'   => 'bg-amber-500',
                        ],
                        'unavailable' => [
                            'label' => 'Habis',
                            'badge' => 'bg-rose-50 text-rose-700 border-rose-200/80',
                            'dot'   => 'bg-rose-500',
                        ],
                    ];
                    $status = $statusMap[$book->stock_status] ?? $statusMap['available'];
                @endphp
                <div class="group bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-xs hover:shadow-xl hover:border-orange-300 transition-all duration-300 flex flex-col h-full hover:-translate-y-1">
                    {{-- Cover Container --}}
                    <a href="{{ route('member.catalog.show', $book) }}" class="relative aspect-[3/4] bg-stone-100 overflow-hidden block">
                        <img src="{{ $book->cover }}" 
                             alt="{{ $book->title }}" 
                             loading="lazy"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">

                        {{-- Gradient Overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-stone-900/60 via-transparent to-black/10 pointer-events-none"></div>

                        {{-- Stock Status Badge --}}
                        <div class="absolute top-2.5 right-2.5">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] sm:text-xs font-semibold backdrop-blur-md shadow-xs {{ $status['badge'] }} border">
                                <span class="w-1.5 h-1.5 rounded-full {{ $status['dot'] }}"></span>
                                {{ $status['label'] }}
                            </span>
                        </div>

                        {{-- Category Tag on Cover --}}
                        @if ($book->category)
                            <div class="absolute bottom-2.5 left-2.5 max-w-[85%]">
                                <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-medium bg-black/60 text-white backdrop-blur-xs truncate shadow-xs">
                                    {{ $book->category }}
                                </span>
                            </div>
                        @endif
                    </a>

                    {{-- Card Body --}}
                    <div class="p-3.5 sm:p-4 flex flex-col flex-1 justify-between gap-3">
                        <div>
                            {{-- Title --}}
                            <a href="{{ route('member.catalog.show', $book) }}" class="block">
                                <h3 class="font-heading font-bold text-stone-900 text-sm sm:text-base leading-snug line-clamp-2 min-h-[2.5rem] group-hover:text-orange-600 transition-colors" title="{{ $book->title }}">
                                    {{ $book->title }}
                                </h3>
                            </a>

                            {{-- Author & Year --}}
                            <div class="mt-1.5 flex items-center gap-1.5 text-xs text-stone-500">
                                <svg class="w-3.5 h-3.5 text-stone-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="truncate" title="{{ $book->author }}">{{ $book->author }}</span>
                                @if ($book->publish_year)
                                    <span class="text-stone-300">&bull;</span>
                                    <span class="shrink-0 text-stone-400">{{ $book->publish_year }}</span>
                                @endif
                            </div>

                            {{-- Stock Info --}}
                            <div class="mt-2.5 flex items-center justify-between text-[11px] text-stone-500 bg-stone-50 px-2.5 py-1.5 rounded-lg border border-stone-100">
                                <span class="text-stone-500 font-medium">Stok Tersedia</span>
                                <span class="font-bold {{ $book->available_stock > 0 ? 'text-stone-800' : 'text-rose-600' }}">
                                    {{ $book->available_stock }} / {{ $book->total_stock }}
                                </span>
                            </div>
                        </div>

                        {{-- Tombol Detail Buku (Jelas & Interaktif) --}}
                        <div class="pt-2 border-t border-stone-100">
                            <a href="{{ route('member.catalog.show', $book) }}" 
                               class="w-full inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-orange-50 hover:bg-orange-600 text-orange-700 hover:text-white border border-orange-200 hover:border-orange-600 text-xs font-semibold transition-all duration-200 shadow-xs group/btn">
                                <svg class="w-3.5 h-3.5 text-orange-600 group-hover/btn:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span>Lihat Detail</span>
                                <svg class="w-3.5 h-3.5 transition-transform duration-200 group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $books->links() }}
        </div>
    @endif
</x-layouts.member>