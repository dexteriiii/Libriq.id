<x-layouts.admin title="Insight & Analitik — Libriq.id">
    {{-- Header --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-bold font-heading text-stone-900">Insight & Analitik Perpustakaan</h1>
                <span class="px-2.5 py-0.5 bg-indigo-100 text-indigo-800 text-xs font-semibold rounded-full">Keputusan Pengadaan</span>
            </div>
            <p class="text-stone-500 text-sm mt-1">Data preferensi peminjam, tren perputaran koleksi, dan panduan pengadaan buku baru.</p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('admin.books.create') }}" class="px-4 py-2.5 bg-orange-600 text-white rounded-xl text-sm font-semibold hover:bg-orange-700 transition-colors shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Koleksi Baru
            </a>
            <a href="{{ route('admin.reports.pdf') }}" target="_blank" class="px-4 py-2.5 bg-white border border-stone-200 text-stone-700 rounded-xl text-sm font-medium hover:bg-stone-50 transition-colors shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Unduh Laporan PDF
            </a>
        </div>
    </div>

    {{-- Top Highlights Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Card 1: Kategori Paling Populer --}}
        <div class="bg-white rounded-2xl p-6 border border-stone-200 shadow-sm relative overflow-hidden">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-stone-400">Kategori Terfavorit</p>
                    <h3 class="text-xl font-bold font-heading text-stone-900 mt-1 truncate">
                        {{ $topCategories->first()?->category ?? 'Belum ada data' }}
                    </h3>
                </div>
                <div class="p-2.5 bg-indigo-50 rounded-xl text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
            </div>
            <p class="text-xs text-stone-500">
                @if($topCategories->isNotEmpty())
                    Menyumbang <strong>{{ round(($topCategories->first()->total_loans / $totalCategorizedLoans) * 100) }}%</strong> dari total peminjaman.
                @else
                    Belum ada sirkulasi.
                @endif
            </p>
        </div>

        {{-- Card 2: Buku Terlaris --}}
        <div class="bg-white rounded-2xl p-6 border border-stone-200 shadow-sm relative overflow-hidden">
            <div class="flex justify-between items-start mb-3">
                <div class="min-w-0 flex-1 pr-2">
                    <p class="text-xs font-semibold uppercase tracking-wider text-stone-400">Buku Paling Dicari</p>
                    <h3 class="text-base font-bold font-heading text-stone-900 mt-1 truncate" title="{{ $topBooks->first()?->title }}">
                        {{ $topBooks->first()?->title ?? 'Belum ada data' }}
                    </h3>
                </div>
                <div class="p-2.5 bg-orange-50 rounded-xl text-orange-600 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                </div>
            </div>
            <p class="text-xs text-stone-500">
                Total dipinjam <strong>{{ $topBooks->first()?->loans_count ?? 0 }} kali</strong>.
            </p>
        </div>

        {{-- Card 3: Perputaran Koleksi (Turnover Rate) --}}
        <div class="bg-white rounded-2xl p-6 border border-stone-200 shadow-sm relative overflow-hidden">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-stone-400">Tingkat Perputaran</p>
                    <h3 class="text-2xl font-bold font-heading text-stone-900 mt-1">{{ $turnoverRate }}x</h3>
                </div>
                <div class="p-2.5 bg-emerald-50 rounded-xl text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </div>
            </div>
            <p class="text-xs text-stone-500">
                Rata-rata frekuensi sirkulasi per eksemplar fisik.
            </p>
        </div>

        {{-- Card 4: Butuh Tambah Stok --}}
        <div class="bg-white rounded-2xl p-6 border border-stone-200 shadow-sm relative overflow-hidden">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-stone-400">Butuh Pengadaan</p>
                    <h3 class="text-2xl font-bold font-heading text-red-600 mt-1">{{ $needRestockBooks->count() }} Judul</h3>
                </div>
                <div class="p-2.5 bg-red-50 rounded-xl text-red-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>
            <p class="text-xs text-stone-500">
                Peminjaman tinggi namun sisa stok <strong>&le; 1</strong> eksemplar.
            </p>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8" x-data="{ trendPeriod: 'monthly' }">
        {{-- Chart 1: Tren Peminjaman --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-stone-200 shadow-sm p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-bold font-heading text-stone-900">Tren Peminjaman Koleksi</h3>
                    <p class="text-stone-500 text-xs mt-0.5">Grafik dinamika aktivitas peminjaman buku oleh anggota.</p>
                </div>
                <div class="flex items-center p-1 bg-stone-100 rounded-xl">
                    <button @click="trendPeriod = 'monthly'; renderTrendChart('monthly')"
                            :class="trendPeriod === 'monthly' ? 'bg-white text-stone-900 shadow-sm' : 'text-stone-600 hover:text-stone-900'"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all">
                        Bulanan
                    </button>
                    <button @click="trendPeriod = 'weekly'; renderTrendChart('weekly')"
                            :class="trendPeriod === 'weekly' ? 'bg-white text-stone-900 shadow-sm' : 'text-stone-600 hover:text-stone-900'"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all">
                        Mingguan
                    </button>
                </div>
            </div>

            <div class="h-72 w-full relative">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        {{-- Chart 2: Komposisi Kategori Favorit --}}
        <div class="bg-white rounded-2xl border border-stone-200 shadow-sm p-6 flex flex-col">
            <div class="mb-4">
                <h3 class="text-lg font-bold font-heading text-stone-900">Distribusi Kategori Favorit</h3>
                <p class="text-stone-500 text-xs mt-0.5">Proporsi peminjaman berdasarkan rumpun kategori.</p>
            </div>

            <div class="h-56 w-full relative flex items-center justify-center my-auto">
                <canvas id="categoryChart"></canvas>
            </div>

            <div class="mt-4 pt-4 border-t border-stone-100 space-y-2">
                @foreach($topCategories->take(4) as $cat)
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-stone-600 truncate max-w-[150px] font-medium">{{ $cat->category }}</span>
                        <div class="flex items-center gap-2">
                            <span class="text-stone-400">{{ $cat->total_loans }} pinjaman</span>
                            <span class="font-bold text-stone-900">{{ round(($cat->total_loans / $totalCategorizedLoans) * 100) }}%</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Main Analytics Content --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        {{-- Table: Buku Paling Sering Dipinjam --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-stone-200 bg-stone-50/50 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold font-heading text-stone-900">Peringkat Buku Terpopuler</h3>
                    <p class="text-stone-500 text-xs mt-0.5">Analisis permintaan judul dan ketersediaan stok fisik.</p>
                </div>
                <span class="text-xs text-stone-500 font-medium">Top {{ $topBooks->count() }} Judul</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-stone-50 text-stone-500 text-xs uppercase tracking-wider border-b border-stone-200">
                            <th class="px-6 py-3 font-semibold text-center w-12">Rank</th>
                            <th class="px-6 py-3 font-semibold">Judul Buku & Kategori</th>
                            <th class="px-6 py-3 font-semibold text-center">Total Dipinjam</th>
                            <th class="px-6 py-3 font-semibold text-center">Sisa Stok</th>
                            <th class="px-6 py-3 font-semibold">Status Pengadaan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-200 text-sm">
                        @forelse($topBooks as $index => $book)
                            <tr class="hover:bg-stone-50/50 transition-colors">
                                <td class="px-6 py-4 text-center">
                                    @if($index === 0)
                                        <span class="inline-flex items-center justify-center w-7 h-7 bg-amber-100 text-amber-800 rounded-full font-bold text-xs">🥇 1</span>
                                    @elseif($index === 1)
                                        <span class="inline-flex items-center justify-center w-7 h-7 bg-stone-200 text-stone-800 rounded-full font-bold text-xs">🥈 2</span>
                                    @elseif($index === 2)
                                        <span class="inline-flex items-center justify-center w-7 h-7 bg-amber-50 text-amber-700 border border-amber-300 rounded-full font-bold text-xs">🥉 3</span>
                                    @else
                                        <span class="text-stone-400 font-medium text-xs">#{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-stone-900 line-clamp-1">{{ $book->title }}</div>
                                    <div class="text-xs text-stone-500 flex items-center gap-2 mt-0.5">
                                        <span>{{ $book->author }}</span>
                                        <span class="text-stone-300">&bull;</span>
                                        <span class="px-2 py-0.5 bg-stone-100 rounded text-stone-600 font-medium">{{ $book->category }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-heading font-bold text-base text-stone-900">{{ $book->loans_count }}</span>
                                    <span class="text-stone-400 text-xs block">kali</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-semibold text-xs {{ $book->available_stock > 0 ? 'text-emerald-700' : 'text-red-600' }}">
                                        {{ $book->available_stock }} / {{ $book->total_stock }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($book->available_stock == 0 && $book->loans_count > 0)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">
                                            <span class="w-1.5 h-1.5 bg-red-600 rounded-full animate-pulse"></span>
                                            Sangat Butuh Restock
                                        </span>
                                    @elseif($book->available_stock <= 2 && $book->loans_count >= 2)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-semibold">
                                            <span class="w-1.5 h-1.5 bg-amber-600 rounded-full"></span>
                                            Prioritas Tambah
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-medium">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                            Stok Memadai
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-stone-400">
                                    Belum ada data peminjaman buku yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Decision Support Card: Rekomendasi Pengadaan --}}
        <div class="bg-white rounded-2xl border border-stone-200 shadow-sm p-6 flex flex-col">
            <div class="flex items-center gap-2 mb-4">
                <div class="p-2 bg-indigo-50 text-indigo-600 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold font-heading text-stone-900">Rekomendasi Pengadaan</h3>
                    <p class="text-stone-400 text-xs">Panduan berbasis data sirkulasi nyata</p>
                </div>
            </div>

            <div class="space-y-4 flex-1">
                {{-- Insight 1: Kategori Prioritas --}}
                <div class="p-4 rounded-xl bg-indigo-50/60 border border-indigo-100">
                    <div class="flex items-center gap-2 text-indigo-900 font-semibold text-xs mb-1">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Prioritas Rumpun Kategori
                    </div>
                    <p class="text-xs text-indigo-950 leading-relaxed">
                        Kategori <strong class="text-indigo-700">"{{ $topCategories->first()?->category ?? 'Umum' }}"</strong> memiliki minat pinjam tertinggi. Disarankan memperbanyak variasi judul baru pada bidang ini.
                    </p>
                </div>

                {{-- Insight 2: Judul yang Harus Segera Ditambah Eksemplar --}}
                <div class="p-4 rounded-xl bg-amber-50/60 border border-amber-100">
                    <div class="flex items-center gap-2 text-amber-900 font-semibold text-xs mb-1">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Koleksi Rasio Stok Rendah
                    </div>
                    <ul class="text-xs text-amber-950 space-y-1.5 mt-2">
                        @forelse($needRestockBooks->take(3) as $book)
                            <li class="flex items-center justify-between">
                                <span class="truncate max-w-[170px]">&bull; {{ $book->title }}</span>
                                <span class="text-red-700 font-bold font-mono">Sisa {{ $book->available_stock }}</span>
                            </li>
                        @empty
                            <li class="text-stone-500 italic">Semua koleksi populer saat ini stoknya memadai.</li>
                        @endforelse
                    </ul>
                </div>

                {{-- Insight 3: Rasio Sirkulasi --}}
                <div class="p-4 rounded-xl bg-stone-50 border border-stone-200">
                    <div class="flex items-center justify-between text-xs mb-1.5">
                        <span class="text-stone-600 font-medium">Efisiensi Koleksi Perpustakaan</span>
                        <span class="font-bold text-stone-900">{{ $turnoverRate > 0.5 ? 'Sangat Aktif' : 'Normal' }}</span>
                    </div>
                    <p class="text-xs text-stone-500 leading-relaxed">
                        Total <strong>{{ $totalLoansCount }} transaksi</strong> dari <strong>{{ $totalCopiesCount }} eksemplar</strong> buku terdaftar.
                    </p>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-stone-200">
                <a href="{{ route('admin.books.create') }}" class="w-full py-2.5 bg-stone-900 hover:bg-stone-800 text-white rounded-xl text-xs font-semibold transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Buka Formulir Pengadaan Koleksi
                </a>
            </div>
        </div>
    </div>

    {{-- Chart.js Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ── Dataset Data ──────────────────────────────────────────────────
            const monthlyLabels = @json($monthlyLabels);
            const monthlyData   = @json($monthlyData);
            const weeklyLabels  = @json($weeklyLabels);
            const weeklyData    = @json($weeklyData);

            const categoryLabels = @json($topCategories->pluck('category')->take(5));
            const categoryData   = @json($topCategories->pluck('total_loans')->take(5));

            // ── 1. Trend Chart (Line) ─────────────────────────────────────────
            const trendCtx = document.getElementById('trendChart').getContext('2d');
            
            // Gradient fill
            const gradient = trendCtx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(234, 88, 12, 0.35)');
            gradient.addColorStop(1, 'rgba(234, 88, 12, 0.0)');

            window.trendChartInstance = new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: monthlyLabels,
                    datasets: [{
                        label: 'Jumlah Peminjaman',
                        data: monthlyData,
                        borderColor: '#ea580c',
                        borderWidth: 2.5,
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#ea580c',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1c1917',
                            padding: 10,
                            titleFont: { family: 'Plus Jakarta Sans', size: 12 },
                            bodyFont: { family: 'Inter', size: 12 },
                            callbacks: {
                                label: function(context) {
                                    return ' ' + context.parsed.y + ' Peminjaman';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                font: { family: 'Inter', size: 11 },
                                color: '#78716c'
                            },
                            grid: { color: '#f5f5f4' }
                        },
                        x: {
                            ticks: {
                                font: { family: 'Inter', size: 11 },
                                color: '#78716c'
                            },
                            grid: { display: false }
                        }
                    }
                }
            });

            // Toggle function for Weekly vs Monthly
            window.renderTrendChart = function(type) {
                if (type === 'monthly') {
                    window.trendChartInstance.data.labels = monthlyLabels;
                    window.trendChartInstance.data.datasets[0].data = monthlyData;
                } else {
                    window.trendChartInstance.data.labels = weeklyLabels;
                    window.trendChartInstance.data.datasets[0].data = weeklyData;
                }
                window.trendChartInstance.update();
            };

            // ── 2. Category Donut Chart ───────────────────────────────────────
            const categoryCtx = document.getElementById('categoryChart').getContext('2d');
            const categoryColors = ['#ea580c', '#4f46e5', '#059669', '#d97706', '#0284c7'];

            new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: categoryLabels.length > 0 ? categoryLabels : ['Belum ada'],
                    datasets: [{
                        data: categoryData.length > 0 ? categoryData : [1],
                        backgroundColor: categoryData.length > 0 ? categoryColors : ['#e7e5e4'],
                        borderWidth: 2,
                        borderColor: '#ffffff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1c1917',
                            padding: 10,
                            callbacks: {
                                label: function(context) {
                                    return ' ' + context.label + ': ' + context.parsed + ' pinjaman';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-layouts.admin>
