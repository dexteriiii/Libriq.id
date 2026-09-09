<x-layouts.member title="{{ $book->title }} — Libriq.id">
    @php
        $statusMap = [
            'available' => ['label' => 'Tersedia', 'color' => 'emerald'],
            'limited' => ['label' => 'Stok Terbatas', 'color' => 'amber'],
            'unavailable' => ['label' => 'Stok Habis', 'color' => 'red'],
        ];
        $status = $statusMap[$book->stock_status];
    @endphp

    <a href="{{ route('member.catalog.index') }}" class="inline-flex items-center gap-1 text-sm text-stone-500 hover:text-stone-900 mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke Katalog
    </a>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="md:col-span-1">
            <div class="aspect-[2/3] rounded-xl overflow-hidden bg-stone-100 border border-stone-200">
                <img src="{{ $book->cover }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
            </div>
        </div>

        <div class="md:col-span-2">
            <span class="inline-block text-xs font-medium px-2.5 py-1 rounded-full bg-{{ $status['color'] }}-50 text-{{ $status['color'] }}-700 mb-3">
                {{ $status['label'] }} ({{ $book->available_stock }}/{{ $book->total_stock }} eksemplar)
            </span>

            <h1 class="font-heading text-2xl font-bold text-stone-900">{{ $book->title }}</h1>
            <p class="text-stone-500 mt-1">{{ $book->author }} &middot; {{ $book->publisher }} ({{ $book->publish_year }})</p>

            <dl class="grid grid-cols-2 gap-4 mt-6 text-sm">
                <div>
                    <dt class="text-stone-400">ISBN</dt>
                    <dd class="font-mono-code text-stone-900">{{ $book->isbn }}</dd>
                </div>
                <div>
                    <dt class="text-stone-400">Kategori</dt>
                    <dd class="text-stone-900">{{ $book->category ?: '—' }}</dd>
                </div>
                <div>
                    <dt class="text-stone-400">Lokasi Rak</dt>
                    <dd class="font-mono-code text-stone-900">{{ $book->rack_location ?: '—' }}</dd>
                </div>
            </dl>

            @if ($book->synopsis)
                <div class="mt-6">
                    <h2 class="font-heading font-semibold text-stone-900 mb-1">Sinopsis</h2>
                    <p class="text-sm text-stone-600 leading-relaxed">{{ $book->synopsis }}</p>
                </div>
            @endif

            <div class="mt-8">
                @if ($book->available_stock > 0)
                    <form method="POST" action="{{ route('member.loans.store', $book) }}">
                        @csrf
                        <button type="submit"
                                class="bg-orange-600 hover:bg-orange-700 text-white font-medium px-6 py-3 rounded-lg text-sm transition-colors">
                            Ajukan Peminjaman
                        </button>
                    </form>
                @else
                    <button type="button" disabled
                            class="bg-stone-200 text-stone-400 font-medium px-6 py-3 rounded-lg text-sm cursor-not-allowed">
                        Stok Tidak Tersedia
                    </button>
                @endif
            </div>
        </div>
    </div>
</x-layouts.member>
