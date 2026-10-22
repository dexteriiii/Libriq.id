<x-layouts.admin title="Dashboard Admin — Libriq.id">
    {{-- Admin Profile & Welcome Banner --}}
    <div class="mb-6 sm:mb-8 bg-white border border-stone-200 rounded-2xl p-4 sm:p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4 sm:gap-6">
        <div class="flex items-center gap-3 sm:gap-4">
            <div class="relative shrink-0">
                <img src="{{ auth()->user()?->avatar_url }}" alt="Avatar {{ auth()->user()?->name }}" class="w-14 h-14 sm:w-16 sm:h-16 rounded-full object-cover border-2 border-orange-500 shadow-sm">
                <span class="absolute bottom-0 right-0 w-3.5 h-3.5 sm:w-4 sm:h-4 bg-emerald-500 border-2 border-white rounded-full" title="Admin Aktif"></span>
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2 flex-wrap">
                    <h1 class="text-lg sm:text-2xl font-bold font-heading text-stone-900 truncate">Halo, {{ auth()->user()?->name }} 👋</h1>
                    <span class="px-2 py-0.5 bg-orange-100 text-orange-800 text-[10px] sm:text-xs font-semibold rounded-full shrink-0">Administrator</span>
                </div>
                <p class="text-stone-500 text-xs sm:text-sm mt-0.5 leading-relaxed">Ringkasan aktivitas sirkulasi dan inventaris perpustakaan Libriq.id hari ini.</p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 shrink-0">
            <a href="{{ route('admin.profile.edit') }}" class="px-3.5 py-2 sm:px-4 sm:py-2.5 bg-stone-50 border border-stone-200 text-stone-700 rounded-xl text-xs sm:text-sm font-medium hover:bg-stone-100 hover:text-stone-900 transition-colors flex items-center justify-center gap-2">
                <svg class="w-4 h-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Ubah Profil
            </a>
            <a href="{{ route('admin.books.create') }}" class="px-3.5 py-2 sm:px-4 sm:py-2.5 bg-orange-600 text-white rounded-xl text-xs sm:text-sm font-semibold hover:bg-orange-700 transition-colors shadow-sm flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Buku
            </a>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-xs">
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm flex items-center justify-between shadow-xs">
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Stats Grid (2 columns on mobile, 4 on desktop) --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-6 sm:mb-8">
        {{-- Card 1: Total Koleksi --}}
        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-stone-200 shadow-sm relative overflow-hidden group">
            <div class="flex justify-between items-start mb-2 sm:mb-4">
                <div class="min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-stone-500 mb-0.5 sm:mb-1">Total Koleksi</p>
                    <h3 class="text-xl sm:text-3xl font-bold font-heading text-stone-900">{{ number_format($totalBooks) }}</h3>
                </div>
                <div class="p-2 sm:p-2.5 bg-orange-50 rounded-xl text-orange-600 shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
            </div>
            <div class="flex items-center text-[11px] sm:text-sm">
                <span class="text-stone-500 truncate">
                    Habis: <strong class="text-red-600">{{ $outOfStockBooks }}</strong> | Sisa: <strong class="text-amber-600">{{ $limitedBooks }}</strong>
                </span>
            </div>
            <div class="absolute -bottom-6 -right-6 w-20 h-20 sm:w-24 sm:h-24 bg-orange-50 rounded-full opacity-50 pointer-events-none"></div>
        </div>

        {{-- Card 2: Dipinjam & Pending --}}
        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-stone-200 shadow-sm relative overflow-hidden group">
            <div class="flex justify-between items-start mb-2 sm:mb-4">
                <div class="min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-stone-500 mb-0.5 sm:mb-1">Dipinjam</p>
                    <h3 class="text-xl sm:text-3xl font-bold font-heading text-stone-900">{{ number_format($totalBorrowed) }}</h3>
                </div>
                <div class="p-2 sm:p-2.5 bg-blue-50 rounded-xl text-blue-600 shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                </div>
            </div>
            <div class="flex items-center text-[11px] sm:text-sm">
                <a href="{{ route('admin.loans.pending') }}" class="text-amber-600 hover:underline font-semibold flex items-center gap-1 truncate">
                    {{ $totalPending }} Menunggu &rarr;
                </a>
            </div>
        </div>

        {{-- Card 3: Terlambat --}}
        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-stone-200 shadow-sm relative overflow-hidden group">
            <div class="flex justify-between items-start mb-2 sm:mb-4">
                <div class="min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-stone-500 mb-0.5 sm:mb-1">Terlambat</p>
                    <h3 class="text-xl sm:text-3xl font-bold font-heading text-stone-900">{{ number_format($totalOverdue) }}</h3>
                </div>
                <div class="p-2 sm:p-2.5 bg-red-50 rounded-xl text-red-600 shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="flex items-center text-[11px] sm:text-sm">
                @if($totalOverdue > 0)
                    <span class="text-red-600 font-semibold flex items-center gap-1 truncate">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Butuh tindak lanjut
                    </span>
                @else
                    <span class="text-emerald-600 font-medium truncate">Aman terkendali</span>
                @endif
            </div>
        </div>

        {{-- Card 4: Denda Belum Dibayar / Anggota --}}
        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-stone-200 shadow-sm relative overflow-hidden group">
            <div class="flex justify-between items-start mb-2 sm:mb-4">
                <div class="min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-stone-500 mb-0.5 sm:mb-1">Denda Belum Bayar</p>
                    <h3 class="text-base sm:text-2xl font-bold font-heading text-stone-900 truncate">Rp {{ number_format($totalUnpaidFine, 0, ',', '.') }}</h3>
                </div>
                <div class="p-2 sm:p-2.5 bg-emerald-50 rounded-xl text-emerald-600 shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="flex items-center text-[11px] sm:text-sm">
                <span class="text-stone-500 truncate">Total Anggota: <strong>{{ number_format($totalMembers) }}</strong></span>
            </div>
        </div>
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Pending Approvals Table --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-stone-200 flex flex-col sm:flex-row sm:items-center justify-between gap-2 bg-stone-50/50">
                <div>
                    <h3 class="text-lg font-bold font-heading text-stone-900">Permintaan Menunggu Persetujuan</h3>
                    <p class="text-xs text-stone-500 mt-0.5">Cocokkan 5 kode angka dari anggota sebelum menyerahkan buku.</p>
                </div>
                <a href="{{ route('admin.loans.pending') }}" class="text-xs sm:text-sm font-semibold text-orange-600 hover:text-orange-700 self-start sm:self-auto">
                    Lihat Semua &rarr;
                </a>
            </div>

            {{-- Quick Code Match Bar --}}
            <div class="px-6 py-3.5 bg-gradient-to-r from-amber-50 to-orange-50/40 border-b border-amber-200/70 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2 text-xs font-semibold text-amber-900">
                    <span class="p-1 bg-amber-500 text-white rounded-md">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </span>
                    <span>Verifikasi Cepat Kode Anggota:</span>
                </div>
                <form method="POST" action="{{ route('admin.loans.verify-code') }}" class="flex items-center gap-2">
                    @csrf
                    <input type="text" name="code" placeholder="5 Digit (cth: 62517)" maxlength="5" pattern="[0-9]{5}" required
                           class="w-36 px-3 py-1.5 text-xs font-mono font-bold text-center bg-white border border-amber-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 shadow-2xs placeholder:font-sans placeholder:font-normal placeholder:text-stone-400" autocomplete="off">
                    <button type="submit" class="px-3.5 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-semibold transition-colors shadow-2xs whitespace-nowrap flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Cocokkan
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-stone-50 text-stone-500 text-xs uppercase tracking-wider">
                            <th class="px-6 py-3 font-medium">Peminjam</th>
                            <th class="px-6 py-3 font-medium">Buku</th>
                            <th class="px-6 py-3 font-medium">Kode Bukti</th>
                            <th class="px-6 py-3 font-medium">Tanggal Ajuan</th>
                            <th class="px-6 py-3 font-medium text-right">Aksi Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-200 text-sm">
                        @forelse($pendingLoans as $loan)
                            <tr class="hover:bg-stone-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-stone-900">{{ $loan->borrower?->name ?? 'User N/A' }}</div>
                                    <div class="text-stone-500 text-xs">{{ $loan->borrower?->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-stone-900 line-clamp-1 font-medium">{{ $loan->book?->title }}</div>
                                    <div class="text-stone-500 text-xs font-mono">ISBN: {{ $loan->book?->isbn }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($loan->verification_code)
                                        <span class="inline-flex items-center font-mono font-black text-xs px-2.5 py-1 bg-amber-100 text-amber-900 rounded-lg border border-amber-300 tracking-wider shadow-2xs">
                                            {{ $loan->verification_code }}
                                        </span>
                                    @else
                                        <span class="text-stone-400 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-stone-500 text-xs">
                                    {{ $loan->created_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <form method="POST" action="{{ route('admin.loans.approve', $loan) }}" class="flex items-center gap-1.5">
                                            @csrf
                                            @if($loan->verification_code)
                                                <input type="text" name="verification_code" placeholder="Ketik kode" maxlength="5" pattern="[0-9]{5}" required
                                                       class="w-24 px-2 py-1 text-xs font-mono text-center border border-stone-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white shadow-2xs" title="Masukkan 5 digit kode verifikasi anggota">
                                            @endif
                                            <button type="submit" @if($loan->book?->available_stock <= 0) disabled @endif class="px-3 py-1 bg-emerald-600 text-white rounded-lg text-xs font-semibold hover:bg-emerald-700 transition-colors shadow-2xs disabled:opacity-50 whitespace-nowrap">
                                                Cocokkan & Setujui
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.loans.reject', $loan) }}" onsubmit="return confirm('Tolak permohonan peminjaman ini?');">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 bg-red-100 text-red-700 rounded-lg text-xs font-medium hover:bg-red-200 transition-colors">
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-stone-500">
                                    Tidak ada permintaan peminjaman pending saat ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Action Center --}}
        <div class="bg-white rounded-2xl border border-stone-200 shadow-sm p-6">
            <h3 class="text-lg font-bold font-heading text-stone-900 mb-4">Aksi Cepat</h3>
            
            <div class="space-y-3">
                <a href="{{ route('admin.books.create') }}" class="w-full flex items-center gap-3 p-3 rounded-xl border border-stone-200 hover:border-orange-500 hover:bg-orange-50 hover:text-orange-700 transition-all text-left group">
                    <div class="p-2 bg-stone-100 rounded-lg group-hover:bg-orange-100 text-stone-600 group-hover:text-orange-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <div>
                        <p class="font-medium">Tambah Buku (Google Books API)</p>
                        <p class="text-xs text-stone-500 group-hover:text-orange-600/80">Input ISBN untuk auto-fill</p>
                    </div>
                </a>
                
                <a href="{{ route('admin.loans.index') }}" class="w-full flex items-center gap-3 p-3 rounded-xl border border-stone-200 hover:border-emerald-500 hover:bg-emerald-50 hover:text-emerald-700 transition-all text-left group">
                    <div class="p-2 bg-stone-100 rounded-lg group-hover:bg-emerald-100 text-stone-600 group-hover:text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    </div>
                    <div>
                        <p class="font-medium">Kelola Sirkulasi & Pengembalian</p>
                        <p class="text-xs text-stone-500 group-hover:text-emerald-600/80">Proses pengembalian & denda</p>
                    </div>
                </a>

                <a href="{{ route('admin.users.create') }}" class="w-full flex items-center gap-3 p-3 rounded-xl border border-stone-200 hover:border-stone-400 hover:bg-stone-50 transition-all text-left group">
                    <div class="p-2 bg-stone-100 rounded-lg group-hover:bg-stone-200 text-stone-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div>
                        <p class="font-medium">Daftarkan Anggota Baru</p>
                        <p class="text-xs text-stone-500">Tambah member offline</p>
                    </div>
                </a>

                <a href="{{ route('admin.insights.index') }}" class="w-full flex items-center gap-3 p-3 rounded-xl border border-indigo-200 bg-indigo-50/40 hover:border-indigo-500 hover:bg-indigo-50 hover:text-indigo-700 transition-all text-left group">
                    <div class="p-2 bg-indigo-100 rounded-lg group-hover:bg-indigo-200 text-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <p class="font-medium text-stone-900 group-hover:text-indigo-700">Insight & Analitik</p>
                            <span class="px-1.5 py-0.5 bg-indigo-600 text-white text-[10px] font-bold rounded">Baru</span>
                        </div>
                        <p class="text-xs text-stone-500 group-hover:text-indigo-600/80">Grafik tren & buku populer</p>
                    </div>
                </a>

                <a href="{{ route('admin.reports.pdf') }}" target="_blank" class="w-full flex items-center gap-3 p-3 rounded-xl border border-red-200 bg-red-50/40 hover:border-red-500 hover:bg-red-50 hover:text-red-700 transition-all text-left group">
                    <div class="p-2 bg-red-100 rounded-lg group-hover:bg-red-200 text-red-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <p class="font-medium text-stone-900 group-hover:text-red-700">Ekspor Laporan PDF</p>
                        </div>
                        <p class="text-xs text-stone-500 group-hover:text-red-600/80">Format resmi SMKN 5 Surakarta</p>
                    </div>
                </a>

                <a href="{{ route('admin.settings.index') }}" class="w-full flex items-center gap-3 p-3 rounded-xl border border-stone-200 hover:border-stone-400 hover:bg-stone-50 transition-all text-left group">
                    <div class="p-2 bg-stone-100 rounded-lg group-hover:bg-stone-200 text-stone-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <p class="font-medium">Pengaturan Sistem</p>
                        <p class="text-xs text-stone-500">Konfigurasi tarif denda & durasi</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-layouts.admin>
