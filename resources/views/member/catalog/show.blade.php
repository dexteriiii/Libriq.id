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

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
        <div class="md:col-span-1">
            <div class="max-w-xs mx-auto md:max-w-none aspect-[2/3] rounded-2xl overflow-hidden bg-stone-100 border border-stone-200 shadow-sm">
                <img src="{{ $book->cover }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
            </div>
        </div>

        <div class="md:col-span-2">
            <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full bg-{{ $status['color'] }}-50 text-{{ $status['color'] }}-700 mb-3 shadow-xs">
                {{ $status['label'] }} ({{ $book->available_stock }}/{{ $book->total_stock }} eksemplar)
            </span>

            <h1 class="font-heading text-xl sm:text-3xl font-bold text-stone-900 leading-tight">{{ $book->title }}</h1>
            <p class="text-stone-500 text-sm sm:text-base mt-1.5">{{ $book->author }} &middot; {{ $book->publisher }} ({{ $book->publish_year }})</p>

            <dl class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4 mt-6 text-sm bg-stone-50/80 p-4 rounded-xl border border-stone-200/60">
                <div>
                    <dt class="text-xs text-stone-400 font-medium">ISBN</dt>
                    <dd class="font-mono-code text-stone-900 font-medium mt-0.5">{{ $book->isbn }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-stone-400 font-medium">Kategori</dt>
                    <dd class="text-stone-900 font-medium mt-0.5">{{ $book->category ?: '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-stone-400 font-medium">Lokasi Rak</dt>
                    <dd class="font-mono-code text-stone-900 font-medium mt-0.5">{{ $book->rack_location ?: '—' }}</dd>
                </div>
            </dl>

            @if ($book->synopsis)
                <div class="mt-6">
                    <h2 class="font-heading font-semibold text-stone-900 mb-1.5 text-base sm:text-lg">Sinopsis</h2>
                    <p class="text-sm text-stone-600 leading-relaxed">{{ $book->synopsis }}</p>
                </div>
            @endif

            <div class="mt-8">
                @if (! auth()->user()->canBorrowBooks())
                    <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 text-sm mb-4">
                        <div class="flex items-center gap-2 font-bold text-red-900">
                            <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Peminjaman Buku Dinonaktifkan
                        </div>
                        <p class="mt-1 text-xs text-red-700 leading-relaxed">{{ auth()->user()->getBorrowBlockReason() }}</p>
                    </div>

                    <button type="button" disabled
                            class="w-full sm:w-auto bg-stone-200 text-stone-400 font-semibold px-6 py-3.5 rounded-xl text-sm cursor-not-allowed">
                        Tidak Dapat Meminjam (Denda / Terlambat)
                    </button>
                @elseif ($book->available_stock > 0)
                    <form method="POST" action="{{ route('member.loans.store', $book) }}">
                        @csrf
                        <button type="submit"
                                class="w-full sm:w-auto bg-orange-600 hover:bg-orange-700 text-white font-semibold px-8 py-3.5 rounded-xl text-sm transition-colors shadow-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            Ajukan Peminjaman
                        </button>
                    </form>
                @else
                    <button type="button" disabled
                            class="w-full sm:w-auto bg-stone-200 text-stone-400 font-semibold px-6 py-3.5 rounded-xl text-sm cursor-not-allowed">
                        Stok Tidak Tersedia
                    </button>
                @endif
            </div>
        </div>
    </div>
</x-layouts.member>
