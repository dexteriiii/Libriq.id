<x-layouts.admin title="Manajemen Katalog — Libriq.id">
    {{-- Page Header --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-heading text-stone-900">Manajemen Katalog</h1>
            <p class="text-stone-500 mt-1">Kelola data buku, stok fisik, dan metadata.</p>
        </div>
        <div class="flex gap-2">
            <button class="px-4 py-2 bg-white border border-stone-200 text-stone-700 rounded-lg text-sm font-medium hover:bg-stone-50 transition-colors shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Filter
            </button>
            <button class="px-4 py-2 bg-orange-600 text-white rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Buku Baru
            </button>
        </div>
    </div>

    {{-- Info Alert (Google Books API Feature) --}}
    <div class="mb-6 bg-blue-50 border border-blue-200 rounded-xl p-4 flex gap-3">
        <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div class="text-sm text-blue-800">
            <strong>Tips:</strong> Saat menambah buku baru, cukup masukkan ISBN dan sistem akan mengambil data secara otomatis dari Google Books API.
        </div>
    </div>

    {{-- Books Table --}}
    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-stone-50 border-b border-stone-200 text-stone-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold">Buku & Metadata</th>
                        <th class="px-6 py-4 font-semibold">Kategori / Rak</th>
                        <th class="px-6 py-4 font-semibold text-center">Stok Total</th>
                        <th class="px-6 py-4 font-semibold text-center">Tersedia</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200 text-sm">
                    {{-- Row 1 --}}
                    <tr class="hover:bg-stone-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex gap-4 items-center">
                                <div class="w-12 h-16 bg-stone-200 rounded shadow-sm overflow-hidden shrink-0">
                                    <img src="https://covers.openlibrary.org/b/isbn/9780134494166-S.jpg" alt="Cover" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h4 class="font-bold text-stone-900 line-clamp-1">Clean Architecture: A Craftsman's Guide to Software Structure and Design</h4>
                                    <p class="text-stone-500 text-xs mt-0.5">Robert C. Martin • 2017</p>
                                    <div class="flex items-center gap-2 mt-1.5">
                                        <span class="text-xs font-mono bg-stone-100 text-stone-600 px-1.5 py-0.5 rounded">ISBN: 9780134494166</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-stone-900">Teknologi & Komputer</div>
                            <div class="text-stone-500 text-xs mt-0.5 font-mono">Rak: T-01-A</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-stone-900 font-medium">5</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 font-bold">
                                3
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-stone-400 hover:text-orange-600 transition-colors p-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.536l12.232-12.232z"/></svg>
                            </button>
                        </td>
                    </tr>

                    {{-- Row 2 --}}
                    <tr class="hover:bg-stone-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex gap-4 items-center">
                                <div class="w-12 h-16 bg-stone-200 rounded shadow-sm overflow-hidden shrink-0">
                                    <img src="https://covers.openlibrary.org/b/isbn/9786024125189-S.jpg" alt="Cover" class="w-full h-full object-cover opacity-80">
                                </div>
                                <div>
                                    <h4 class="font-bold text-stone-900 line-clamp-1">Filosofi Teras</h4>
                                    <p class="text-stone-500 text-xs mt-0.5">Henry Manampiring • 2018</p>
                                    <div class="flex items-center gap-2 mt-1.5">
                                        <span class="text-xs font-mono bg-stone-100 text-stone-600 px-1.5 py-0.5 rounded">ISBN: 9786024125189</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-stone-900">Filsafat & Psikologi</div>
                            <div class="text-stone-500 text-xs mt-0.5 font-mono">Rak: F-04-C</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-stone-900 font-medium">3</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-100 text-red-700 font-bold" title="Habis dipinjam">
                                0
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-stone-400 hover:text-orange-600 transition-colors p-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.536l12.232-12.232z"/></svg>
                            </button>
                        </td>
                    </tr>

                    {{-- Row 3 --}}
                    <tr class="hover:bg-stone-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex gap-4 items-center">
                                <div class="w-12 h-16 bg-stone-200 rounded shadow-sm flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-stone-900 line-clamp-1">Laut Bercerita</h4>
                                    <p class="text-stone-500 text-xs mt-0.5">Leila S. Chudori • 2017</p>
                                    <div class="flex items-center gap-2 mt-1.5">
                                        <span class="text-xs font-mono bg-stone-100 text-stone-600 px-1.5 py-0.5 rounded">ISBN: 9786024246945</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-stone-900">Fiksi & Sastra</div>
                            <div class="text-stone-500 text-xs mt-0.5 font-mono">Rak: S-02-B</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-stone-900 font-medium">8</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-100 text-amber-700 font-bold" title="Terbatas">
                                2
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-stone-400 hover:text-orange-600 transition-colors p-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.536l12.232-12.232z"/></svg>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        {{-- Pagination (Mock) --}}
        <div class="px-6 py-4 border-t border-stone-200 flex items-center justify-between bg-stone-50">
            <span class="text-sm text-stone-500">Menampilkan 1 hingga 3 dari 12,450 buku</span>
            <div class="flex gap-1">
                <button disabled class="px-3 py-1.5 border border-stone-200 rounded text-sm text-stone-400 bg-white">Mundur</button>
                <button class="px-3 py-1.5 border border-stone-200 rounded text-sm text-stone-700 bg-white hover:bg-stone-50">Maju</button>
            </div>
        </div>
    </div>
</x-layouts.admin>
