<x-layouts.guest :title="'Libriq.id — Perpustakaan Digital SMKN 5 Surakarta'">

@php
    /* ── Ambil 8 buku dari DB untuk ditampilkan di katalog ── */
    $featuredBooks = \App\Models\Book::select('id','title','author','category','available_stock','cover_url','cover_path','synopsis')
        ->orderByDesc('available_stock')
        ->limit(8)
        ->get();
@endphp

<div x-data="{
    mobileMenuOpen: false,
    scrolled: false,
    init() { window.addEventListener('scroll', () => { this.scrolled = window.scrollY > 20; }); }
}" @scroll.window="scrolled = window.scrollY > 20">

{{-- ═══════════════════════════════════════════════════════
     NAVBAR
═══════════════════════════════════════════════════════ --}}
<nav :class="scrolled ? 'bg-white/95 shadow-md backdrop-blur-md' : 'bg-transparent'"
     class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="#beranda" class="flex items-center gap-2.5 group">
                <div class="w-9 h-9 bg-orange-600 rounded-xl flex items-center justify-center shadow-lg group-hover:bg-orange-700 transition-colors">
                    <svg class="w-5 h-5 text-white" viewBox="0 0 40 40" fill="none">
                        <rect x="4"  y="6"  width="8"  height="28" rx="2" fill="white" opacity="0.9"/>
                        <rect x="14" y="4"  width="8"  height="32" rx="2" fill="white"/>
                        <rect x="24" y="8"  width="8"  height="26" rx="2" fill="white" opacity="0.8"/>
                        <path d="M34 8 L38 6 L38 34 L34 32Z" fill="white" opacity="0.6"/>
                    </svg>
                </div>
                <span :class="scrolled ? 'text-stone-900' : 'text-white'"
                      class="text-xl font-extrabold font-heading tracking-tight transition-colors">
                    Libriq<span class="text-orange-500">.id</span>
                </span>
            </a>

            {{-- Desktop Nav --}}
            <div class="hidden md:flex items-center gap-1">
                @foreach([['#beranda','Beranda'],['#fitur','Fitur'],['#katalog','Katalog'],['#tentang','Tentang']] as [$href,$label])
                <a href="{{ $href }}"
                   :class="scrolled ? 'text-stone-700 hover:text-orange-600' : 'text-white/90 hover:text-white'"
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:bg-white/10">
                    {{ $label }}
                </a>
                @endforeach
            </div>

            {{-- CTA Buttons --}}
            <div class="hidden md:flex items-center gap-3">
                <a href="{{ route('login') }}"
                   :class="scrolled ? 'text-stone-700 hover:text-orange-600 border-stone-300 hover:border-orange-400' : 'text-white/90 hover:text-white border-white/40 hover:border-white'"
                   class="px-4 py-2 rounded-lg text-sm font-semibold border transition-all duration-200">
                    Masuk
                </a>
                <a href="{{ route('register') }}"
                   class="px-5 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold rounded-lg shadow-lg shadow-orange-500/30 transition-all duration-200 hover:-translate-y-0.5">
                    Daftar
                </a>
            </div>

            {{-- Mobile Hamburger --}}
            <button @click="mobileMenuOpen = !mobileMenuOpen"
                    :class="scrolled ? 'text-stone-700' : 'text-white'"
                    class="md:hidden p-2 rounded-lg transition-colors">
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu Drawer --}}
    <div x-show="mobileMenuOpen" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="md:hidden bg-white border-t border-stone-100 shadow-xl">
        <div class="px-4 py-4 space-y-1">
            @foreach([['#beranda','Beranda'],['#fitur','Fitur'],['#katalog','Katalog'],['#tentang','Tentang']] as [$href,$label])
            <a href="{{ $href }}" @click="mobileMenuOpen = false"
               class="block px-4 py-3 text-stone-700 hover:text-orange-600 hover:bg-orange-50 rounded-lg font-medium text-sm transition-colors">
                {{ $label }}
            </a>
            @endforeach
            <div class="pt-3 border-t border-stone-100 flex flex-col gap-2">
                <a href="{{ route('login') }}" class="block px-4 py-3 text-center text-stone-700 border border-stone-200 rounded-lg font-semibold text-sm hover:border-orange-400 hover:text-orange-600 transition-colors">Masuk</a>
                <a href="{{ route('register') }}" class="block px-4 py-3 text-center bg-orange-600 text-white rounded-lg font-semibold text-sm hover:bg-orange-700 transition-colors">Daftar Sekarang</a>
            </div>
        </div>
    </div>
</nav>

{{-- ═══════════════════════════════════════════════════════
     HERO SECTION
═══════════════════════════════════════════════════════ --}}
<section id="beranda" class="relative min-h-screen flex items-center overflow-hidden bg-gradient-to-br from-orange-700 via-orange-600 to-amber-500">

    {{-- Decorative book shapes --}}
    <div class="absolute inset-0 opacity-10 pointer-events-none">
        <div class="absolute top-20 left-10 w-40 h-52 border-2 border-white rounded-2xl rotate-[-12deg]"></div>
        <div class="absolute top-32 right-20 w-32 h-44 border-2 border-white rounded-2xl rotate-[8deg]"></div>
        <div class="absolute bottom-24 left-1/4 w-36 h-48 border-2 border-white rounded-2xl rotate-[5deg]"></div>
        <div class="absolute bottom-40 right-10 w-28 h-36 border-2 border-white rounded-2xl rotate-[-6deg]"></div>
        <div class="absolute top-1/2 left-1/2 w-20 h-28 border-2 border-white rounded-2xl rotate-[15deg]"></div>
        <div class="absolute top-16 left-1/3 w-24 h-32 border-2 border-white rounded-xl rotate-[-20deg]"></div>
    </div>

    {{-- Blobs --}}
    <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-black/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/3"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-20 md:pt-32">
        <div class="grid lg:grid-cols-2 gap-12 items-center">

            {{-- Left: Text --}}
            <div class="text-white text-center lg:text-left">
                <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-full px-4 py-2 text-sm font-medium mb-6">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    Perpustakaan Digital SMKN 5 Surakarta
                </div>

                <h1 class="text-4xl md:text-5xl xl:text-6xl font-extrabold font-heading leading-tight mb-6">
                    Kelola Perpustakaan<br>
                    <span class="text-amber-200">Digital Anda</span><br>
                    dengan Mudah
                </h1>

                <p class="text-lg text-orange-100 leading-relaxed mb-8 max-w-lg mx-auto lg:mx-0">
                    Platform digitalisasi sirkulasi buku fisik — dari katalogisasi otomatis via Google Books,
                    peminjaman mandiri siswa, hingga perhitungan denda keterlambatan secara otomatis.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="#katalog"
                       class="inline-flex items-center justify-center gap-2 px-7 py-3.5 bg-white text-orange-600 font-bold rounded-xl shadow-xl hover:bg-orange-50 transition-all duration-200 hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        Jelajahi Katalog
                    </a>
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center justify-center gap-2 px-7 py-3.5 bg-orange-800/50 backdrop-blur-sm text-white font-bold rounded-xl border border-white/30 hover:bg-orange-800/70 transition-all duration-200 hover:-translate-y-0.5">
                        Mulai Sekarang
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                </div>

                <div class="mt-12 flex flex-wrap gap-6 justify-center lg:justify-start">
                    @foreach([[$featuredBooks->count().'+','Koleksi Buku'],['200+','Siswa Aktif'],['99%','Uptime Sistem']] as [$num,$label])
                    <div class="text-center">
                        <div class="text-2xl font-extrabold font-heading text-white">{{ $num }}</div>
                        <div class="text-xs text-orange-200 font-medium">{{ $label }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Right: Floating card mockup --}}
            <div class="hidden lg:flex justify-center items-center relative">
                <div class="bg-white rounded-2xl shadow-2xl p-6 w-80 relative z-10">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-stone-900">Peminjaman Aktif</div>
                            <div class="text-xs text-stone-500">Status Real-time</div>
                        </div>
                        <span class="ml-auto text-xs bg-emerald-100 text-emerald-700 font-semibold px-2 py-1 rounded-full">Aktif</span>
                    </div>
                    <div class="space-y-3">
                        @foreach($featuredBooks->take(3) as $heroBook)
                        <div class="flex items-center gap-3 p-3 bg-stone-50 rounded-xl">
                            {{-- Cover thumbnail --}}
                            @if($heroBook->cover_url || $heroBook->cover_path)
                            <img src="{{ $heroBook->cover }}"
                                 alt="{{ $heroBook->title }}"
                                 class="w-8 h-10 object-cover rounded-md flex-shrink-0 shadow-sm"
                                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                            <div class="w-8 h-10 bg-gradient-to-b from-orange-400 to-orange-600 rounded-md flex-shrink-0 hidden"></div>
                            @else
                            <div class="w-8 h-10 bg-gradient-to-b from-orange-400 to-orange-600 rounded-md flex-shrink-0"></div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <div class="text-xs font-semibold text-stone-900 truncate">{{ $heroBook->title }}</div>
                                <div class="text-xs text-stone-400 truncate">{{ $heroBook->author }}</div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <div class="text-xs font-semibold {{ $heroBook->available_stock > 0 ? 'text-emerald-600' : 'text-red-500' }}">
                                    {{ $heroBook->available_stock > 0 ? 'Tersedia' : 'Habis' }}
                                </div>
                                <div class="text-xs text-stone-400">{{ $heroBook->available_stock }} stok</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="absolute -top-4 -right-4 bg-emerald-500 text-white text-xs font-bold px-3 py-2 rounded-xl shadow-lg flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Stok Tersedia
                </div>

                <div class="absolute -bottom-4 -left-4 bg-white text-stone-900 text-xs font-semibold px-3 py-2.5 rounded-xl shadow-xl border border-stone-100 flex items-center gap-2">
                    <span class="text-lg">🔔</span>
                    <div>
                        <div class="font-bold">Denda Otomatis</div>
                        <div class="text-stone-400 font-normal">Rp 1.000/hari &gt;7 hari</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Wave divider --}}
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path d="M0 80L60 69.3C120 59 240 37 360 32C480 27 600 37 720 42.7C840 48 960 48 1080 42.7C1200 37 1320 27 1380 21.3L1440 16V80H0Z" fill="#fafaf9"/>
        </svg>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     FITUR UNGGULAN
═══════════════════════════════════════════════════════ --}}
<section id="fitur" class="py-20 bg-stone-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-14">
            <span class="inline-block text-orange-600 text-sm font-bold tracking-widest uppercase mb-3">Fitur Unggulan</span>
            <h2 class="text-3xl md:text-4xl font-extrabold font-heading text-stone-900 mb-4">
                Semua yang Kamu Butuhkan<br>dalam Satu Platform
            </h2>
            <p class="text-stone-500 max-w-xl mx-auto">
                Libriq.id dirancang khusus untuk memudahkan pengelolaan perpustakaan sekolah dari mana saja dan kapan saja.
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>','color'=>'orange','title'=>'Katalog Terintegrasi','desc'=>'Auto-fetch metadata buku (judul, penulis, sinopsis, cover) langsung dari Google Books API hanya dengan input ISBN.'],
                ['icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>','color'=>'blue','title'=>'Sirkulasi Otomatis','desc'=>'Proses peminjaman & pengembalian digital dengan kode verifikasi unik, perhitungan denda Rp 1.000/hari setelah 7 hari terlambat.'],
                ['icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>','color'=>'emerald','title'=>'Multi-Role Access','desc'=>'Akses terpisah untuk Admin (pustakawan) dan Siswa (member) dengan dashboard dan fitur yang disesuaikan perannya.'],
                ['icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>','color'=>'purple','title'=>'Laporan & Statistik','desc'=>'Dashboard analitik real-time: jumlah buku dipinjam, overdue, pendapatan denda, dan tren peminjaman bulanan.'],
            ] as $f)
            @php
                $clr = ['orange'=>['bg'=>'bg-orange-100','ic'=>'text-orange-600'],'blue'=>['bg'=>'bg-blue-100','ic'=>'text-blue-600'],'emerald'=>['bg'=>'bg-emerald-100','ic'=>'text-emerald-600'],'purple'=>['bg'=>'bg-purple-100','ic'=>'text-purple-600']][$f['color']];
            @endphp
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-stone-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
                <div class="{{ $clr['bg'] }} w-12 h-12 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 {{ $clr['ic'] }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        {!! $f['icon'] !!}
                    </svg>
                </div>
                <h3 class="font-bold font-heading text-stone-900 mb-2">{{ $f['title'] }}</h3>
                <p class="text-sm text-stone-500 leading-relaxed">{{ $f['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     KATALOG BUKU (data nyata dari database)
═══════════════════════════════════════════════════════ --}}
<section id="katalog" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10">
            <div>
                <span class="inline-block text-orange-600 text-sm font-bold tracking-widest uppercase mb-3">Koleksi Buku</span>
                <h2 class="text-3xl md:text-4xl font-extrabold font-heading text-stone-900">
                    Buku Tersedia di Perpustakaan
                </h2>
                <p class="text-stone-500 text-sm mt-2">{{ \App\Models\Book::count() }} judul tersedia · Diperbarui secara real-time</p>
            </div>
            <a href="{{ route('login') }}"
               class="inline-flex items-center gap-2 text-orange-600 hover:text-orange-700 font-semibold text-sm group flex-shrink-0">
                Lihat Semua Koleksi
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>

        {{-- Book Grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-5">
            @foreach($featuredBooks as $book)
            @php
                $status = $book->stock_status; // available | limited | unavailable
                $statusConfig = match($status) {
                    'available'   => ['bg'=>'bg-emerald-500','text'=>'Tersedia','dot'=>'bg-emerald-400'],
                    'limited'     => ['bg'=>'bg-amber-500','text'=>'Terbatas','dot'=>'bg-amber-400'],
                    default       => ['bg'=>'bg-red-500','text'=>'Habis','dot'=>'bg-red-400'],
                };
                $coverSrc = $book->cover; // pakai accessor dari model
            @endphp
            <div class="group flex flex-col">
                <div class="relative overflow-hidden rounded-xl shadow-md group-hover:shadow-xl transition-all duration-300 group-hover:-translate-y-1 flex-1">

                    {{-- Book Cover Image --}}
                    <div class="aspect-[3/4] relative bg-stone-100 overflow-hidden">
                        <img src="{{ $coverSrc }}"
                             alt="{{ $book->title }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                             loading="lazy"
                             onerror="this.onerror=null; this.parentElement.classList.add('cover-fallback'); this.style.display='none';">

                        {{-- Fallback jika gambar gagal load --}}
                        <div class="absolute inset-0 bg-gradient-to-br from-orange-500 to-orange-700 items-center justify-center p-4 text-white text-center hidden cover-fallback-inner">
                            <div class="absolute left-0 top-0 bottom-0 w-3 bg-black/20"></div>
                            <div class="relative">
                                <svg class="w-10 h-10 mx-auto mb-2 opacity-70" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <p class="text-xs font-bold leading-tight">{{ Str::limit($book->title, 40) }}</p>
                            </div>
                        </div>

                        {{-- Status Badge --}}
                        <div class="absolute top-2 left-2">
                            <span class="{{ $statusConfig['bg'] }} text-white text-xs font-bold px-2 py-0.5 rounded-full shadow flex items-center gap-1">
                                <span class="w-1.5 h-1.5 {{ $statusConfig['dot'] }} rounded-full"></span>
                                {{ $statusConfig['text'] }}
                            </span>
                        </div>

                        {{-- Stock Badge --}}
                        <div class="absolute top-2 right-2">
                            <span class="bg-black/50 backdrop-blur-sm text-white text-xs font-semibold px-2 py-0.5 rounded-full">
                                {{ $book->available_stock }} stok
                            </span>
                        </div>

                        {{-- Hover Overlay --}}
                        <div class="absolute inset-0 bg-black/70 opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col items-center justify-center p-4 text-center">
                            @if($book->synopsis && $book->synopsis !== '-')
                            <p class="text-white text-xs leading-relaxed mb-4 line-clamp-4">
                                {{ Str::limit($book->synopsis, 100) }}
                            </p>
                            @endif
                            @if($book->available_stock > 0)
                            <a href="{{ route('login') }}"
                               class="inline-flex items-center gap-1.5 text-xs font-bold bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg transition-colors shadow-lg">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                </svg>
                                Pinjam Buku
                            </a>
                            @else
                            <span class="text-xs font-semibold text-red-300 bg-red-900/50 px-3 py-1.5 rounded-lg">
                                Stok Habis
                            </span>
                            @endif
                        </div>
                    </div>

                    {{-- Info Below --}}
                    <div class="bg-white px-3 py-2.5">
                        <p class="text-xs font-bold text-stone-900 truncate leading-tight" title="{{ $book->title }}">
                            {{ $book->title }}
                        </p>
                        <p class="text-xs text-stone-400 truncate mt-0.5">{{ $book->author }}</p>
                        @if($book->category)
                        <span class="inline-block mt-1 text-xs text-orange-600 bg-orange-50 px-1.5 py-0.5 rounded font-medium">
                            {{ $book->category }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Script: handle image error → show fallback --}}
        <script>
            document.querySelectorAll('.aspect-\\[3\\/4\\] img').forEach(img => {
                img.addEventListener('error', function() {
                    this.style.display = 'none';
                    const fb = this.parentElement.querySelector('.cover-fallback-inner');
                    if (fb) fb.classList.remove('hidden'), fb.classList.add('flex');
                });
            });
        </script>

        <div class="text-center mt-10">
            <p class="text-stone-500 text-sm mb-4">Dan masih banyak koleksi lainnya. Login untuk melihat semua katalog dan ajukan peminjaman.</p>
            <a href="{{ route('login') }}"
               class="inline-flex items-center gap-2 px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl shadow-lg shadow-orange-500/30 transition-all duration-200 hover:-translate-y-0.5">
                Masuk & Pinjam Buku
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     CARA PINJAM BUKU
═══════════════════════════════════════════════════════ --}}
<section class="py-20 bg-stone-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-14">
            <span class="inline-block text-orange-600 text-sm font-bold tracking-widest uppercase mb-3">Panduan</span>
            <h2 class="text-3xl md:text-4xl font-extrabold font-heading text-stone-900 mb-4">
                Cara Meminjam Buku
            </h2>
            <p class="text-stone-500 max-w-xl mx-auto">
                Proses peminjaman buku di Perpustakaan SMKN 5 Surakarta kini lebih mudah dan cepat melalui Libriq.id.
            </p>
        </div>

        <div class="grid md:grid-cols-5 gap-4 items-start">
            @php
            $steps = [
                ['01','Aktivasi Akun','Siswa aktif SMKN 5 Surakarta melakukan aktivasi akun dengan NIS dan nama lengkap yang terdaftar.','text-orange-600','bg-orange-100'],
                ['02','Cari Buku','Jelajahi katalog digital dan temukan buku yang ingin kamu pinjam di menu Katalog.','text-blue-600','bg-blue-100'],
                ['03','Ajukan Pinjaman','Tekan tombol "Ajukan Peminjaman". Sistem akan membuat kode verifikasi unik otomatis.','text-emerald-600','bg-emerald-100'],
                ['04','Verifikasi di Perpus','Tunjukkan kode verifikasi ke petugas perpustakaan untuk mendapatkan buku fisiknya.','text-purple-600','bg-purple-100'],
                ['05','Kembalikan Tepat Waktu','Kembalikan dalam 7 hari. Keterlambatan &gt;7 hari dikenakan denda Rp 1.000/hari.','text-red-600','bg-red-100'],
            ];
            @endphp
            @foreach($steps as $i => [$num,$title,$desc,$tc,$bgc])
            <div class="relative">
                @if($i < count($steps)-1)
                <div class="hidden md:block absolute top-6 left-[calc(50%+2.5rem)] right-0 h-0.5 bg-stone-200 z-0"></div>
                @endif
                <div class="relative z-10 flex flex-col items-center text-center">
                    <div class="{{ $bgc }} w-12 h-12 rounded-full flex items-center justify-center mb-4 ring-4 ring-white shadow-sm">
                        <span class="{{ $tc }} text-sm font-extrabold font-mono">{{ $num }}</span>
                    </div>
                    <h3 class="font-bold font-heading text-stone-900 text-sm mb-2">{{ $title }}</h3>
                    <p class="text-xs text-stone-500 leading-relaxed">{!! $desc !!}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Ketentuan denda --}}
        <div class="mt-12 bg-orange-50 border border-orange-200 rounded-2xl p-6 flex flex-col md:flex-row gap-4 items-start">
            <div class="text-3xl flex-shrink-0">📌</div>
            <div>
                <h4 class="font-bold text-orange-800 font-heading mb-2">Ketentuan Peminjaman Perpustakaan SMKN 5 Surakarta</h4>
                <ul class="text-sm text-orange-700 space-y-1.5 list-disc list-inside">
                    <li>Maksimal peminjaman buku sesuai kuota yang ditetapkan perpustakaan</li>
                    <li>Durasi peminjaman <strong>7 hari kerja</strong> sejak tanggal persetujuan petugas</li>
                    <li>Keterlambatan &gt;7 hari dikenakan denda <strong>Rp 1.000 per hari</strong></li>
                    <li>Siswa yang masih memiliki denda atau buku <em>overdue</em> tidak dapat meminjam buku baru</li>
                    <li>Buku yang hilang atau rusak wajib diganti sesuai ketentuan perpustakaan</li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     KEUNGGULAN / CALLOUT
═══════════════════════════════════════════════════════ --}}
<section id="tentang" class="py-20 bg-gradient-to-br from-orange-700 via-orange-600 to-amber-500 relative overflow-hidden">

    <div class="absolute inset-0 opacity-10 pointer-events-none">
        <div class="absolute -top-10 -right-10 w-72 h-72 bg-white rounded-full blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 w-72 h-72 bg-black rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center text-white mb-14">
            <span class="inline-block text-orange-200 text-sm font-bold tracking-widest uppercase mb-3">Keunggulan Sistem</span>
            <h2 class="text-3xl md:text-4xl font-extrabold font-heading mb-4">Mengapa Memilih Libriq.id?</h2>
            <p class="text-orange-100 max-w-xl mx-auto">Dibangun khusus untuk institusi pendidikan dengan teknologi modern dan arsitektur yang andal.</p>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-14">
            @foreach([
                ['⚡','10x','Lebih Cepat','Proses sirkulasi digital vs manual'],
                ['🎯','100%','Akurasi Data','Database terstruktur & tervalidasi'],
                ['☁️','99.9%','Uptime','Infrastruktur cloud Supabase'],
                ['🛡️','RBAC','Keamanan Peran','Hak akses terpisah Admin & Siswa'],
            ] as [$emoji,$val,$label,$desc])
            <div class="bg-white/15 backdrop-blur-sm border border-white/20 rounded-2xl p-6 text-center text-white hover:bg-white/20 transition-colors">
                <div class="text-3xl mb-2">{{ $emoji }}</div>
                <div class="text-3xl font-extrabold font-heading mb-1">{{ $val }}</div>
                <div class="font-semibold text-sm mb-1">{{ $label }}</div>
                <div class="text-orange-200 text-xs">{{ $desc }}</div>
            </div>
            @endforeach
        </div>

        <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-8 text-white">
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <h3 class="text-2xl font-extrabold font-heading mb-4">Tentang Libriq.id</h3>
                    <p class="text-orange-100 leading-relaxed mb-4">
                        Libriq.id adalah sistem manajemen perpustakaan digital yang dikembangkan khusus untuk
                        <strong class="text-white">SMKN 5 Surakarta</strong>. Platform ini mengintegrasikan
                        seluruh alur sirkulasi buku fisik dalam satu platform berbasis web yang modern.
                    </p>
                    <p class="text-orange-100 leading-relaxed">
                        Dibangun di atas <strong class="text-white">Laravel 12</strong> dengan UI responsif menggunakan
                        <strong class="text-white">Tailwind CSS</strong> dan interaktivitas <strong class="text-white">Alpine.js</strong>,
                        didukung basis data <strong class="text-white">Supabase (PostgreSQL)</strong>.
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    @foreach([['Laravel 12','Backend Framework'],['Tailwind CSS','UI Styling'],['Google Books API','Data Katalog'],['Supabase','Cloud Database'],['Alpine.js','Interaktivitas'],['Carbon','Kalkulasi Tanggal']] as [$tech,$role])
                    <div class="bg-white/10 rounded-xl px-4 py-3">
                        <div class="font-bold text-sm">{{ $tech }}</div>
                        <div class="text-orange-200 text-xs">{{ $role }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     CTA BANNER
═══════════════════════════════════════════════════════ --}}
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="bg-stone-900 rounded-3xl p-10 md:p-14 relative overflow-hidden">
            <div class="absolute inset-0 opacity-5 pointer-events-none">
                <div class="absolute top-0 right-0 w-64 h-64 bg-orange-500 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-orange-500 rounded-full translate-y-1/2 -translate-x-1/2"></div>
            </div>
            <div class="relative z-10">
                <span class="text-4xl block mb-4">📚</span>
                <h2 class="text-3xl md:text-4xl font-extrabold font-heading text-white mb-4">Siap Mulai Meminjam Buku?</h2>
                <p class="text-stone-400 mb-8 max-w-lg mx-auto">
                    Login dengan akun siswa SMKN 5 Surakarta dan nikmati kemudahan perpustakaan digital sekarang juga.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl shadow-lg shadow-orange-500/40 transition-all duration-200 hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Masuk Sekarang
                    </a>
                    <a href="{{ route('activate') }}"
                       class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-white/10 hover:bg-white/20 text-white font-bold rounded-xl border border-white/20 transition-all duration-200">
                        Aktivasi Akun Siswa
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     FOOTER
═══════════════════════════════════════════════════════ --}}
<footer class="bg-stone-900 text-stone-400 pt-14 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-3 gap-10 mb-10">

            <div>
                <div class="flex items-center gap-2.5 mb-4">
                    <div class="w-9 h-9 bg-orange-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" viewBox="0 0 40 40" fill="none">
                            <rect x="4"  y="6"  width="8"  height="28" rx="2" fill="white" opacity="0.9"/>
                            <rect x="14" y="4"  width="8"  height="32" rx="2" fill="white"/>
                            <rect x="24" y="8"  width="8"  height="26" rx="2" fill="white" opacity="0.8"/>
                            <path d="M34 8 L38 6 L38 34 L34 32Z" fill="white" opacity="0.6"/>
                        </svg>
                    </div>
                    <span class="text-xl font-extrabold font-heading text-white tracking-tight">
                        Libriq<span class="text-orange-500">.id</span>
                    </span>
                </div>
                <p class="text-sm leading-relaxed mb-4">
                    Sistem Manajemen Perpustakaan Digital untuk SMKN 5 Surakarta.
                    Digitalisasi sirkulasi buku fisik secara efisien dan modern.
                </p>
                <div class="flex items-center gap-2 text-xs">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    <span class="text-green-400 font-medium">Sistem Aktif</span>
                </div>
            </div>

            <div>
                <h4 class="text-white font-bold font-heading text-sm mb-4 uppercase tracking-wider">Navigasi</h4>
                <ul class="space-y-2.5 text-sm">
                    @foreach([['#beranda','Beranda'],['#fitur','Fitur Unggulan'],['#katalog','Katalog Buku'],['#tentang','Tentang Sistem']] as [$href,$label])
                    <li>
                        <a href="{{ $href }}" class="hover:text-orange-400 transition-colors flex items-center gap-2">
                            <span class="w-1 h-1 bg-stone-600 rounded-full"></span>{{ $label }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold font-heading text-sm mb-4 uppercase tracking-wider">Institusi</h4>
                <div class="text-sm space-y-3">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span>SMKN 5 Surakarta</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Jl. Adi Sucipto No.42, Surakarta, Jawa Tengah 57145</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <span>Perpustakaan Sekolah</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-stone-800 pt-6 flex flex-col md:flex-row items-center justify-between gap-3 text-xs">
            <p>&copy; {{ date('Y') }} <span class="text-orange-400 font-semibold">Libriq.id</span> — Perpustakaan Digital SMKN 5 Surakarta. Hak Cipta Dilindungi.</p>
            <span>Dibangun dengan ❤️ menggunakan Laravel &amp; Tailwind CSS</span>
        </div>
    </div>
</footer>

</div>{{-- end x-data --}}
</x-layouts.guest>
