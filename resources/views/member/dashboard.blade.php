<x-layouts.member title="Beranda — Libriq.id">
    {{-- Member Profile & Welcome Banner --}}
    <div class="mb-6 sm:mb-8 bg-white border border-stone-200 rounded-2xl p-4 sm:p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4 sm:gap-6">
        <div class="flex items-center gap-3 sm:gap-4">
            <div class="relative shrink-0">
                <img src="{{ auth()->user()->avatar_url }}" alt="Avatar {{ auth()->user()->name }}" class="w-14 h-14 sm:w-16 sm:h-16 rounded-full object-cover border-2 border-orange-500 shadow-sm">
                <span class="absolute bottom-0 right-0 w-3.5 h-3.5 sm:w-4 sm:h-4 bg-emerald-500 border-2 border-white rounded-full" title="Member Aktif"></span>
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2 flex-wrap">
                    <h1 class="font-heading text-lg sm:text-2xl font-bold text-stone-900 truncate">Halo, {{ auth()->user()->name }} 👋</h1>
                    <span class="px-2 py-0.5 bg-orange-100 text-orange-800 text-[10px] sm:text-xs font-semibold rounded-full shrink-0">Anggota</span>
                </div>
                <div class="flex flex-wrap items-center gap-x-2 sm:gap-x-3 gap-y-1 text-xs text-stone-500 mt-1">
                    <span class="font-mono bg-stone-100 px-2 py-0.5 rounded text-stone-600 font-medium">ID: {{ auth()->user()->member_id ?? 'Member' }}</span>
                    <span class="truncate">{{ auth()->user()->email }}</span>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 shrink-0">
            <a href="{{ route('member.profile.edit') }}" class="px-3.5 py-2 sm:px-4 sm:py-2.5 bg-stone-50 border border-stone-200 text-stone-700 rounded-xl text-xs sm:text-sm font-medium hover:bg-stone-100 hover:text-stone-900 transition-colors flex items-center justify-center gap-2">
                <svg class="w-4 h-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Ubah Profil
            </a>
            <a href="{{ route('member.catalog.index') }}" class="px-3.5 py-2 sm:px-4 sm:py-2.5 bg-orange-600 text-white rounded-xl text-xs sm:text-sm font-semibold hover:bg-orange-700 transition-colors shadow-sm flex items-center justify-center gap-2">
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

    {{-- Overdue & Fine Warning Banner --}}
    @if(auth()->user()->hasOverdueLoans() || auth()->user()->totalUnpaidFines() > 0)
        <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
            <div class="flex items-start sm:items-center gap-3">
                <div class="p-2.5 bg-red-100 rounded-xl text-red-600 shrink-0 mt-0.5 sm:mt-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <p class="font-bold text-red-900 font-heading">Peminjaman Buku Baru Ditangguhkan</p>
                    <p class="text-xs text-red-700 mt-0.5 leading-relaxed">{{ auth()->user()->getBorrowBlockReason() }}</p>
                </div>
            </div>
            <a href="{{ route('member.loans.history') }}" class="w-full sm:w-auto text-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-semibold whitespace-nowrap transition-colors shadow-sm shrink-0">
                Lihat Detail Denda & Sirkulasi &rarr;
            </a>
        </div>
    @endif

    {{-- Metric Cards (2 columns on mobile, 4 on desktop) --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6 sm:mb-8">
        <div class="bg-white rounded-xl border border-stone-200 p-4 sm:p-5 shadow-sm">
            <p class="text-xs sm:text-sm text-stone-500 font-medium">Pinjaman Aktif</p>
            <p class="font-heading text-2xl sm:text-3xl font-bold text-orange-600 mt-1">{{ $stats['active_count'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-stone-200 p-4 sm:p-5 shadow-sm">
            <p class="text-xs sm:text-sm text-stone-500 font-medium">Segera Tempo</p>
            <p class="font-heading text-2xl sm:text-3xl font-bold text-amber-500 mt-1">{{ $stats['due_soon_count'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-stone-200 p-4 sm:p-5 shadow-sm">
            <p class="text-xs sm:text-sm text-stone-500 font-medium">Terlambat</p>
            <p class="font-heading text-2xl sm:text-3xl font-bold text-red-600 mt-1">{{ $stats['overdue_count'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-stone-200 p-4 sm:p-5 shadow-sm">
            <p class="text-xs sm:text-sm text-stone-500 font-medium">Denda Aktif</p>
            <p class="font-heading text-lg sm:text-2xl font-bold {{ $stats['unpaid_fine'] > 0 ? 'text-red-600' : 'text-emerald-600' }} mt-1 truncate">
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
                        @php 
                            $badge = $loan->statusBadge(); 
                            $isOverdue = $loan->isOverdue() || $loan->status === 'overdue';
                            $currentFine = $loan->calculateCurrentFine();
                        @endphp
                        <li class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-5 py-4 {{ $isOverdue ? 'bg-red-50/40' : '' }}">
                            <div class="flex items-center gap-4 min-w-0 flex-1">
                                <img src="{{ $loan->book->cover }}" alt="{{ $loan->book->title }}" class="w-12 h-16 object-cover rounded-md bg-stone-100 flex-shrink-0 shadow-sm">
                                <div class="min-w-0 flex-1">
                                    <p class="font-medium text-stone-900 truncate">{{ $loan->book->title }}</p>
                                    <p class="text-sm text-stone-500 truncate">{{ $loan->book->author }}</p>
                                    @if ($loan->due_date)
                                        <div class="flex flex-wrap items-center gap-x-2 gap-y-0.5 mt-1 text-xs">
                                            <span class="{{ $isOverdue ? 'text-red-600 font-semibold' : 'text-stone-400' }}">
                                                Jatuh tempo: {{ $loan->due_date->format('d M Y') }}
                                            </span>
                                            @if($isOverdue)
                                                <span class="text-red-600 font-bold bg-red-100 px-1.5 py-0.5 rounded text-[10px]">
                                                    Terlambat {{ $loan->daysOverdue() }} hari
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                    @if($currentFine > 0)
                                        <div class="text-xs font-bold text-red-600 mt-1 flex items-center gap-1">
                                            <span>Denda: Rp {{ number_format($currentFine, 0, ',', '.') }}</span>
                                            @if(! $loan->fine_paid_at)
                                                <span class="text-red-500 font-normal text-[10px]">(Belum Lunas)</span>
                                            @else
                                                <span class="text-emerald-600 font-normal text-[10px]">(Lunas)</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 shrink-0 self-end sm:self-center">
                                <span class="text-xs font-medium px-2.5 py-1 rounded-full whitespace-nowrap
                                    bg-{{ $badge['color'] }}-50 text-{{ $badge['color'] }}-700">
                                    {{ $badge['label'] }}
                                </span>

                                @if ($loan->return_status === 'pending')
                                    <span class="px-2.5 py-1 bg-amber-50 text-amber-700 text-xs font-semibold rounded-lg border border-amber-200">
                                        Menunggu Konfirmasi Admin
                                    </span>
                                @else
                                    <form method="POST" action="{{ route('member.loans.return', $loan) }}"
                                          onsubmit="return confirm('Ajukan pengembalian buku ini? Silakan serahkan buku fisik ke petugas perpustakaan.');">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-emerald-600 text-white rounded-lg text-xs font-semibold hover:bg-emerald-700 transition-colors shadow-sm">
                                            Kembalikan Buku
                                        </button>
                                    </form>
                                @endif

                                @if ($currentFine > 0 && ! $loan->fine_paid_at)
                                    @if ($loan->fine_payment_status === 'pending')
                                        <span class="px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-lg border border-blue-200">
                                            Verifikasi Denda Diajukan
                                        </span>
                                    @else
                                        <form method="POST" action="{{ route('member.loans.pay-fine', $loan) }}"
                                              onsubmit="return confirm('Konfirmasi bahwa kamu sudah/akan membayar denda cash sebesar Rp {{ number_format($currentFine, 0, ',', '.') }} ke admin?');">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded-lg text-xs font-semibold hover:bg-blue-700 transition-colors shadow-sm">
                                                Konfirmasi Bayar Cash
                                            </button>
                                        </form>
                                    @endif
                                @endif

                                @if (! $isOverdue && $loan->status === 'borrowed' && $loan->return_status !== 'pending')
                                    @if ($loan->renewal_status === 'pending')
                                        <span class="px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-lg border border-blue-200">
                                            Perpanjangan Diajukan
                                        </span>
                                    @elseif ($loan->hasPendingReservation())
                                        <span class="px-2.5 py-1 bg-stone-100 text-stone-500 text-xs font-medium rounded-lg" title="Ada antrean reservasi dari anggota lain">
                                            Ada Antrean
                                        </span>
                                    @else
                                        <form method="POST" action="{{ route('member.loans.renew', $loan) }}"
                                              onsubmit="return confirm('Ajukan perpanjangan masa peminjaman untuk buku ini?');">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-orange-600 text-white rounded-lg text-xs font-semibold hover:bg-orange-700 transition-colors shadow-sm">
                                                Perpanjang
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
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