<x-layouts.member title="Katalog Buku — Libriq.id">
    {{-- Header Halaman --}}
    <div class="mb-6">
        <h1 class="font-heading text-2xl font-bold text-stone-900">Katalog Buku</h1>
        <p class="text-stone-500 text-sm mt-1">Cari dan temukan buku yang tersedia di perpustakaan.</p>
    </div>

    {{-- Panel Pencarian & Filter (mengikuti gaya panel putih rounded-xl + shadow pada desain referensi) --}}
    <div class="bg-white rounded-xl border border-stone-200 shadow-sm p-4 mb-6">
        <form method="GET" action="{{ route('member.catalog.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                </svg>
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari judul, penulis, atau ISBN..."
                       class="w-full h-11 pl-9 pr-3 rounded-lg bg-stone-50 border border-stone-200 text-sm text-stone-900 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-orange-600 focus:border-orange-600 focus:bg-white transition-colors">
            </div>

            <select name="category" onchange="this.form.submit()"
                    class="h-11 rounded-lg bg-stone-50 border border-stone-200 text-sm text-stone-700 px-3 focus:outline-none focus:ring-2 focus:ring-orange-600 focus:border-orange-600 cursor-pointer">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category }}" @selected($activeCategory === $category)>{{ $category }}</option>
                @endforeach
            </select>

            <button type="submit" class="h-11 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium px-5 rounded-lg shadow-sm transition-colors">
                Cari
            </button>
        </form>
    </div>

    @if ($books->isEmpty())
        {{-- Empty State --}}
        <div class="bg-white rounded-xl border border-stone-200 py-16 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-10 w-10 text-stone-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
            </svg>
            <p class="text-stone-500 text-sm">Tidak ada buku yang cocok dengan pencarianmu.</p>
        </div>
    @else
        {{-- Ringkasan jumlah hasil (data dari paginator yang sudah ada, tanpa logika baru) --}}
        <div class="flex items-center justify-between mb-4 px-1">
            <p class="text-xs text-stone-500">
                Menampilkan <span class="font-semibold text-stone-700">{{ $books->firstItem() }}–{{ $books->lastItem() }}</span>
                dari <span class="font-semibold text-stone-700">{{ $books->total() }}</span> judul buku
            </p>
        </div>

        {{-- Grid Katalog --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
            @foreach ($books as $book)
                @php
                    $statusMap = [
                        'available' => ['label' => 'Tersedia', 'color' => 'emerald'],
                        'limited' => ['label' => 'Terbatas', 'color' => 'amber'],
                        'unavailable' => ['label' => 'Habis', 'color' => 'red'],
                    ];
                    $status = $statusMap[$book->stock_status];
                @endphp
                <a href="{{ route('member.catalog.show', $book) }}" class="group">
                    <div class="aspect-[2/3] rounded-xl overflow-hidden bg-stone-100 border border-stone-200 relative shadow-sm group-hover:shadow-md transition-shadow duration-300">
                        <img src="{{ $book->cover }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">

                        {{-- Gradient overlay agar badge tetap kontras di atas cover apa pun --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-stone-900/50 via-transparent to-transparent"></div>

                        <span class="absolute top-2 right-2 text-[11px] font-semibold px-2 py-0.5 rounded-full bg-{{ $status['color'] }}-50 text-{{ $status['color'] }}-700 shadow-sm">
                            {{ $status['label'] }}
                        </span>
                    </div>
                    <p class="mt-2 text-sm font-medium text-stone-900 line-clamp-2 group-hover:text-orange-600 transition-colors">{{ $book->title }}</p>
                    <p class="text-xs text-stone-500 truncate">{{ $book->author }}</p>
                </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $books->links() }}
        </div>
    @endif
</x-layouts.member>