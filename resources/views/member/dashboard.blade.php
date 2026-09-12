<x-layouts.member title="Beranda — Libriq.id">
    {{-- Member Profile & Welcome Banner --}}
    <div class="mb-8 bg-white border border-stone-200 rounded-2xl p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="relative shrink-0">
                <img src="{{ auth()->user()->avatar_url }}" alt="Avatar {{ auth()->user()->name }}" class="w-16 h-16 rounded-full object-cover border-2 border-orange-500 shadow-sm">
                <span class="absolute bottom-0 right-0 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full" title="Member Aktif"></span>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="font-heading text-xl sm:text-2xl font-bold text-stone-900">Halo, {{ auth()->user()->name }} 👋</h1>
                    <span class="px-2.5 py-0.5 bg-orange-100 text-orange-800 text-xs font-semibold rounded-full">Anggota</span>
                </div>
                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-stone-500 mt-1">
                    <span class="font-mono bg-stone-100 px-2 py-0.5 rounded text-stone-600 font-medium">ID: {{ auth()->user()->member_id ?? 'Member' }}</span>
                    <span>{{ auth()->user()->email }}</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('member.profile.edit') }}" class="px-4 py-2.5 bg-stone-50 border border-stone-200 text-stone-700 rounded-xl text-sm font-medium hover:bg-stone-100 hover:text-stone-900 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Ubah Foto / Profil
            </a>
            <a href="{{ route('member.catalog.index') }}" class="px-4 py-2.5 bg-orange-600 text-white rounded-xl text-sm font-semibold hover:bg-orange-700 transition-colors shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                Jelajahi Katalog
            </a>
        </div>
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