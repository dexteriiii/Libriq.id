<x-layouts.admin title="Dashboard Admin — Libriq.id">
    {{-- Page Header --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-heading text-stone-900">Dashboard Statistik</h1>
            <p class="text-stone-500 mt-1">Ringkasan aktivitas sirkulasi dan inventaris perpustakaan.</p>
        </div>
        <div class="flex gap-2">
            <button class="px-4 py-2 bg-white border border-stone-200 text-stone-700 rounded-lg text-sm font-medium hover:bg-stone-50 transition-colors shadow-sm">
                Unduh Laporan
            </button>
            <a href="{{ route('admin.books') ?? '#' }}" class="px-4 py-2 bg-orange-600 text-white rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors shadow-sm">
                Tambah Buku
            </a>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Card 1 --}}
        <div class="bg-white rounded-2xl p-6 border border-stone-200 shadow-sm relative overflow-hidden group">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-sm font-medium text-stone-500 mb-1">Total Koleksi</p>
                    <h3 class="text-3xl font-bold font-heading text-stone-900">12,450</h3>
                </div>
                <div class="p-2.5 bg-orange-50 rounded-xl text-orange-600 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
            </div>
            <div class="flex items-center text-sm">
                <span class="text-emerald-600 font-medium flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    24
                </span>
                <span class="text-stone-500 ml-2">buku baru bulan ini</span>
            </div>
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-orange-50 rounded-full opacity-50 pointer-events-none"></div>
        </div>

        {{-- Card 2 --}}
        <div class="bg-white rounded-2xl p-6 border border-stone-200 shadow-sm relative overflow-hidden group">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-sm font-medium text-stone-500 mb-1">Sedang Dipinjam</p>
                    <h3 class="text-3xl font-bold font-heading text-stone-900">428</h3>
                </div>
                <div class="p-2.5 bg-blue-50 rounded-xl text-blue-600 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                </div>
            </div>
            <div class="flex items-center text-sm">
                <span class="text-amber-500 font-medium flex items-center gap-1">
                    12 Pending Request
                </span>
            </div>
        </div>

        {{-- Card 3 --}}
        <div class="bg-white rounded-2xl p-6 border border-stone-200 shadow-sm relative overflow-hidden group">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-sm font-medium text-stone-500 mb-1">Buku Terlambat</p>
                    <h3 class="text-3xl font-bold font-heading text-stone-900">35</h3>
                </div>
                <div class="p-2.5 bg-red-50 rounded-xl text-red-600 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="flex items-center text-sm">
                <span class="text-red-600 font-medium flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Butuh tindak lanjut
                </span>
            </div>
        </div>

        {{-- Card 4 --}}
        <div class="bg-white rounded-2xl p-6 border border-stone-200 shadow-sm relative overflow-hidden group">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-sm font-medium text-stone-500 mb-1">Pendapatan Denda</p>
                    <h3 class="text-3xl font-bold font-heading text-stone-900">Rp 450K</h3>
                </div>
                <div class="p-2.5 bg-emerald-50 rounded-xl text-emerald-600 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="flex items-center text-sm">
                <span class="text-stone-500">Bulan September 2026</span>
            </div>
        </div>
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Recent Activity Table --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-stone-200 flex justify-between items-center bg-stone-50/50">
                <h3 class="text-lg font-bold font-heading text-stone-900">Sirkulasi Terbaru</h3>
                <a href="#" class="text-sm font-medium text-orange-600 hover:text-orange-700">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-stone-50 text-stone-500 text-xs uppercase tracking-wider">
                            <th class="px-6 py-3 font-medium">Peminjam</th>
                            <th class="px-6 py-3 font-medium">Buku</th>
                            <th class="px-6 py-3 font-medium">Status</th>
                            <th class="px-6 py-3 font-medium">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-200 text-sm">
                        <tr class="hover:bg-stone-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-stone-900">Budi Santoso</div>
                                <div class="text-stone-500 text-xs">M-2026-001</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-stone-900 line-clamp-1">Clean Architecture: A Craftsman's Guide</div>
                                <div class="text-stone-500 text-xs font-mono">ISBN: 9780134494166</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                    Pending Request
                                </span>
                            </td>
                            <td class="px-6 py-4 text-stone-500">Baru saja</td>
                        </tr>
                        <tr class="hover:bg-stone-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-stone-900">Siti Aminah</div>
                                <div class="text-stone-500 text-xs">M-2026-045</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-stone-900 line-clamp-1">Filosofi Teras</div>
                                <div class="text-stone-500 text-xs font-mono">ISBN: 9786024125189</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Dipinjam
                                </span>
                            </td>
                            <td class="px-6 py-4 text-stone-500">2 jam lalu</td>
                        </tr>
                        <tr class="hover:bg-stone-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-stone-900">Andi Wijaya</div>
                                <div class="text-stone-500 text-xs">M-2026-112</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-stone-900 line-clamp-1">The Pragmatic Programmer</div>
                                <div class="text-stone-500 text-xs font-mono">ISBN: 9780135957059</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Overdue (H+2)
                                </span>
                            </td>
                            <td class="px-6 py-4 text-stone-500 text-red-600 font-medium">Jatuh Tempo: 5 Sep</td>
                        </tr>
                        <tr class="hover:bg-stone-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-stone-900">Diana Putri</div>
                                <div class="text-stone-500 text-xs">M-2026-089</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-stone-900 line-clamp-1">Bumi Manusia</div>
                                <div class="text-stone-500 text-xs font-mono">ISBN: 9789799731234</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                    Dikembalikan
                                </span>
                            </td>
                            <td class="px-6 py-4 text-stone-500">Kemarin</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Action Center --}}
        <div class="bg-white rounded-2xl border border-stone-200 shadow-sm p-6">
            <h3 class="text-lg font-bold font-heading text-stone-900 mb-4">Aksi Cepat</h3>
            
            <div class="space-y-3">
                <button class="w-full flex items-center gap-3 p-3 rounded-xl border border-stone-200 hover:border-orange-500 hover:bg-orange-50 hover:text-orange-700 transition-all text-left group">
                    <div class="p-2 bg-stone-100 rounded-lg group-hover:bg-orange-100 text-stone-600 group-hover:text-orange-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <div>
                        <p class="font-medium">Pinjam Buku Manual</p>
                        <p class="text-xs text-stone-500 group-hover:text-orange-600/80">Proses peminjaman di meja</p>
                    </div>
                </button>
                
                <button class="w-full flex items-center gap-3 p-3 rounded-xl border border-stone-200 hover:border-emerald-500 hover:bg-emerald-50 hover:text-emerald-700 transition-all text-left group">
                    <div class="p-2 bg-stone-100 rounded-lg group-hover:bg-emerald-100 text-stone-600 group-hover:text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="font-medium">Pengembalian (Scan)</p>
                        <p class="text-xs text-stone-500 group-hover:text-emerald-600/80">Terima pengembalian buku</p>
                    </div>
                </button>

                <button class="w-full flex items-center gap-3 p-3 rounded-xl border border-stone-200 hover:border-stone-400 hover:bg-stone-50 transition-all text-left group">
                    <div class="p-2 bg-stone-100 rounded-lg group-hover:bg-stone-200 text-stone-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div>
                        <p class="font-medium">Daftarkan Anggota Baru</p>
                        <p class="text-xs text-stone-500">Tambah member offline</p>
                    </div>
                </button>
            </div>
        </div>
    </div>
</x-layouts.admin>
