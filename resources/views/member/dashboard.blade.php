<x-layouts.member title="Beranda — Libriq.id">
    <div class="mb-6">
        <h1 class="font-heading text-2xl font-bold text-stone-900">Halo, {{ auth()->user()->name }} 👋</h1>
        <p class="text-stone-500 text-sm mt-1">Berikut ringkasan aktivitas peminjamanmu.</p>
    </div>

    @if (session('success'))
        <div class="rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 mb-6">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 mb-6">
            {{ session('error') }}
        </div>
    @endif

    {{-- Metric Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl border border-stone-200 p-5">
            <p class="text-sm text-stone-500">Pinjaman Aktif</p>
            <p class="font-heading text-3xl font-bold text-orange-600 mt-1">{{ $stats['active_count'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-stone-200 p-5">
            <p class="text-sm text-stone-500">Segera Jatuh Tempo</p>
            <p class="font-heading text-3xl font-bold text-amber-500 mt-1">{{ $stats['due_soon_count'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-stone-200 p-5">
            <p class="text-sm text-stone-500">Terlambat</p>
            <p class="font-heading text-3xl font-bold text-red-600 mt-1">{{ $stats['overdue_count'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-stone-200 p-5">
            <p class="text-sm text-stone-500">Denda Belum Lunas</p>
            <p class="font-heading text-3xl font-bold {{ $stats['unpaid_fine'] > 0 ? 'text-red-600' : 'text-emerald-600' }} mt-1">
                Rp{{ number_format($stats['unpaid_fine'], 0, ',', '.') }}
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Active Loans --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-stone-200">
            <div class="flex items-center justify-between px-5 py-4 border-b border-stone-200">
                <h2 class="font-heading font-semibold text-stone-900">Pinjaman Aktif</h2>
                <a href="{{ route('member.loans.history') }}" class="text-sm text-orange-600 hover:text-orange-700 font-medium">Lihat semua</a>
            </div>

            @if ($activeLoans->isEmpty())
                <div class="px-5 py-10 text-center">
                    <p class="text-stone-500 text-sm">Belum ada pinjaman aktif. Cari buku di katalog untuk mulai meminjam.</p>
                    <a href="{{ route('member.catalog.index') }}" class="inline-block mt-3 text-sm font-medium text-orange-600 hover:text-orange-700">Jelajahi Katalog →</a>
                </div>
            @else
                <ul class="divide-y divide-stone-100">
                    @foreach ($activeLoans as $loan)
                        @php $badge = $loan->statusBadge(); @endphp
                        <li class="flex items-center gap-4 px-5 py-4">
                            <img src="{{ $loan->book->cover }}" alt="{{ $loan->book->title }}" class="w-12 h-16 object-cover rounded-md bg-stone-100 flex-shrink-0">
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-stone-900 truncate">{{ $loan->book->title }}</p>
                                <p class="text-sm text-stone-500 truncate">{{ $loan->book->author }}</p>
                                @if ($loan->due_date)
                                    <p class="text-xs text-stone-400 mt-0.5">Jatuh tempo: {{ $loan->due_date->format('d M Y') }}</p>
                                @endif
                            </div>
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full whitespace-nowrap
                                bg-{{ $badge['color'] }}-50 text-{{ $badge['color'] }}-700">
                                {{ $badge['label'] }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Recent History --}}
        <div class="bg-white rounded-xl border border-stone-200">
            <div class="px-5 py-4 border-b border-stone-200">
                <h2 class="font-heading font-semibold text-stone-900">Riwayat Terbaru</h2>
            </div>

            @if ($recentHistory->isEmpty())
                <div class="px-5 py-10 text-center">
                    <p class="text-stone-500 text-sm">Belum ada riwayat pengembalian.</p>
                </div>
            @else
                <ul class="divide-y divide-stone-100">
                    @foreach ($recentHistory as $loan)
                        <li class="px-5 py-3">
                            <p class="text-sm font-medium text-stone-900 truncate">{{ $loan->book->title }}</p>
                            <p class="text-xs text-stone-400">Dikembalikan {{ $loan->return_date ? $loan->return_date->format('d M Y') : '—' }}</p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-layouts.member>