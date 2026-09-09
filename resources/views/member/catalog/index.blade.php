<x-layouts.member title="Katalog Buku — Libriq.id">
    <div class="mb-6">
        <h1 class="font-heading text-2xl font-bold text-stone-900">Katalog Buku</h1>
        <p class="text-stone-500 text-sm mt-1">Cari dan temukan buku yang tersedia di perpustakaan.</p>
    </div>

    <form method="GET" action="{{ route('member.catalog.index') }}" class="flex flex-col sm:flex-row gap-3 mb-6">
        <div class="relative flex-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
            </svg>
            <input type="text" name="q" value="{{ $search }}" placeholder="Cari judul, penulis, atau ISBN..."
                   class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-stone-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-600 focus:border-orange-600">
        </div>

        <select name="category" onchange="this.form.submit()"
                class="rounded-lg border border-stone-200 text-sm px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-600">
            <option value="">Semua Kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category }}" @selected($activeCategory === $category)>{{ $category }}</option>
            @endforeach
        </select>

        <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
            Cari
        </button>
    </form>

    @if ($books->isEmpty())
        <div class="bg-white rounded-xl border border-stone-200 py-16 text-center">
            <p class="text-stone-500 text-sm">Tidak ada buku yang cocok dengan pencarianmu.</p>
        </div>
    @else
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
                    <div class="aspect-[2/3] rounded-lg overflow-hidden bg-stone-100 border border-stone-200 relative">
                        <img src="{{ $book->cover }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <span class="absolute top-2 right-2 text-[11px] font-medium px-2 py-0.5 rounded-full bg-{{ $status['color'] }}-50 text-{{ $status['color'] }}-700">
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
