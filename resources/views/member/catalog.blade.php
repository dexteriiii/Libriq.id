<x-layouts.member title="Eksplorasi Katalog — Libriq.id">
    {{-- Hero Section --}}
    <div class="bg-gradient-to-br from-orange-600 to-orange-800 rounded-3xl p-8 sm:p-12 mb-10 text-white relative overflow-hidden">
        {{-- Decorative elements --}}
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <div class="absolute -top-10 -right-10 w-64 h-64 border-4 border-white rounded-full"></div>
            <div class="absolute top-20 right-40 w-16 h-16 border-2 border-white rounded-lg rotate-12"></div>
            <div class="absolute bottom-10 left-20 w-24 h-24 border-2 border-white rounded-full"></div>
        </div>
        
        <div class="relative z-10 max-w-2xl">
            <h1 class="text-3xl sm:text-5xl font-extrabold font-heading leading-tight mb-4">
                Temukan Buku Inspiratif Selanjutnya
            </h1>
            <p class="text-orange-100 text-lg mb-8 max-w-xl leading-relaxed">
                Jelajahi puluhan ribu koleksi digital dan fisik. Pinjam buku dengan mudah, kapan saja dan di mana saja.
            </p>
            
            {{-- Quick Filter Chips --}}
            <div class="flex flex-wrap gap-2">
                <button class="px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur rounded-full text-sm font-medium transition-colors border border-white/10">Terbaru</button>
                <button class="px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur rounded-full text-sm font-medium transition-colors border border-white/10">Terpopuler</button>
                <button class="px-4 py-2 bg-white text-orange-700 rounded-full text-sm font-semibold transition-colors shadow-sm">Fiksi</button>
                <button class="px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur rounded-full text-sm font-medium transition-colors border border-white/10">Sains & Teknologi</button>
                <button class="px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur rounded-full text-sm font-medium transition-colors border border-white/10">Sejarah</button>
            </div>
        </div>
    </div>

    {{-- Section Title --}}
    <div class="flex items-end justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold font-heading text-stone-900">Rekomendasi Untukmu</h2>
            <p class="text-stone-500 mt-1">Berdasarkan kategori kesukaanmu.</p>
        </div>
    </div>

    {{-- Books Grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        
        {{-- Book Card 1 --}}
        <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow group flex flex-col h-full">
            <div class="aspect-[2/3] bg-stone-100 relative overflow-hidden border-b border-stone-100">
                <img src="https://covers.openlibrary.org/b/isbn/9780134494166-L.jpg" alt="Cover" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                
                {{-- Stock Badge --}}
                <div class="absolute top-3 right-3 bg-emerald-500 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-sm backdrop-blur-sm border border-white/20">
                    Tersedia
                </div>
            </div>
            <div class="p-4 flex flex-col flex-1">
                <div class="text-xs font-semibold text-orange-600 mb-1.5 uppercase tracking-wide">Teknologi</div>
                <h3 class="font-bold text-stone-900 font-heading leading-snug line-clamp-2 mb-1 group-hover:text-orange-600 transition-colors">
                    Clean Architecture: A Craftsman's Guide
                </h3>
                <p class="text-sm text-stone-500 line-clamp-1 mb-4">Robert C. Martin</p>
                
                <div class="mt-auto">
                    <button class="w-full py-2.5 bg-orange-50 hover:bg-orange-600 text-orange-700 hover:text-white rounded-xl text-sm font-semibold transition-colors focus:ring-2 focus:ring-orange-500/50">
                        Pinjam Buku
                    </button>
                </div>
            </div>
        </div>

        {{-- Book Card 2 (Habis) --}}
        <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow group flex flex-col h-full">
            <div class="aspect-[2/3] bg-stone-100 relative overflow-hidden border-b border-stone-100">
                <img src="https://covers.openlibrary.org/b/isbn/9786024125189-L.jpg" alt="Cover" class="w-full h-full object-cover grayscale opacity-80">
                
                {{-- Stock Badge --}}
                <div class="absolute top-3 right-3 bg-red-600 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-sm">
                    Habis
                </div>
            </div>
            <div class="p-4 flex flex-col flex-1">
                <div class="text-xs font-semibold text-orange-600 mb-1.5 uppercase tracking-wide">Filsafat</div>
                <h3 class="font-bold text-stone-900 font-heading leading-snug line-clamp-2 mb-1 group-hover:text-orange-600 transition-colors">
                    Filosofi Teras
                </h3>
                <p class="text-sm text-stone-500 line-clamp-1 mb-4">Henry Manampiring</p>
                
                <div class="mt-auto">
                    <button class="w-full py-2.5 bg-stone-100 text-stone-600 rounded-xl text-sm font-semibold transition-colors focus:ring-2 focus:ring-stone-500/50">
                        Ajukan Antrian
                    </button>
                </div>
            </div>
        </div>

        {{-- Book Card 3 (Terbatas) --}}
        <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow group flex flex-col h-full">
            <div class="aspect-[2/3] bg-stone-100 relative overflow-hidden border-b border-stone-100 flex items-center justify-center">
                {{-- Fallback cover --}}
                <svg class="w-16 h-16 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                
                {{-- Stock Badge --}}
                <div class="absolute top-3 right-3 bg-amber-500 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-sm">
                    Sisa 2
                </div>
            </div>
            <div class="p-4 flex flex-col flex-1">
                <div class="text-xs font-semibold text-orange-600 mb-1.5 uppercase tracking-wide">Fiksi</div>
                <h3 class="font-bold text-stone-900 font-heading leading-snug line-clamp-2 mb-1 group-hover:text-orange-600 transition-colors">
                    Laut Bercerita
                </h3>
                <p class="text-sm text-stone-500 line-clamp-1 mb-4">Leila S. Chudori</p>
                
                <div class="mt-auto">
                    <button class="w-full py-2.5 bg-orange-50 hover:bg-orange-600 text-orange-700 hover:text-white rounded-xl text-sm font-semibold transition-colors focus:ring-2 focus:ring-orange-500/50">
                        Pinjam Buku
                    </button>
                </div>
            </div>
        </div>

        {{-- Book Card 4 --}}
        <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow group flex flex-col h-full">
            <div class="aspect-[2/3] bg-stone-100 relative overflow-hidden border-b border-stone-100">
                <img src="https://covers.openlibrary.org/b/isbn/9780135957059-L.jpg" alt="Cover" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute top-3 right-3 bg-emerald-500 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-sm">
                    Tersedia
                </div>
            </div>
            <div class="p-4 flex flex-col flex-1">
                <div class="text-xs font-semibold text-orange-600 mb-1.5 uppercase tracking-wide">Teknologi</div>
                <h3 class="font-bold text-stone-900 font-heading leading-snug line-clamp-2 mb-1 group-hover:text-orange-600 transition-colors">
                    The Pragmatic Programmer
                </h3>
                <p class="text-sm text-stone-500 line-clamp-1 mb-4">David Thomas, Andrew Hunt</p>
                
                <div class="mt-auto">
                    <button class="w-full py-2.5 bg-orange-50 hover:bg-orange-600 text-orange-700 hover:text-white rounded-xl text-sm font-semibold transition-colors focus:ring-2 focus:ring-orange-500/50">
                        Pinjam Buku
                    </button>
                </div>
            </div>
        </div>

        {{-- Book Card 5 --}}
        <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow group flex flex-col h-full">
            <div class="aspect-[2/3] bg-stone-100 relative overflow-hidden border-b border-stone-100">
                <img src="https://covers.openlibrary.org/b/isbn/9789799731234-L.jpg" alt="Cover" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute top-3 right-3 bg-emerald-500 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-sm">
                    Tersedia
                </div>
            </div>
            <div class="p-4 flex flex-col flex-1">
                <div class="text-xs font-semibold text-orange-600 mb-1.5 uppercase tracking-wide">Sastra Klasik</div>
                <h3 class="font-bold text-stone-900 font-heading leading-snug line-clamp-2 mb-1 group-hover:text-orange-600 transition-colors">
                    Bumi Manusia
                </h3>
                <p class="text-sm text-stone-500 line-clamp-1 mb-4">Pramoedya Ananta Toer</p>
                
                <div class="mt-auto">
                    <button class="w-full py-2.5 bg-orange-50 hover:bg-orange-600 text-orange-700 hover:text-white rounded-xl text-sm font-semibold transition-colors focus:ring-2 focus:ring-orange-500/50">
                        Pinjam Buku
                    </button>
                </div>
            </div>
        </div>

    </div>

    {{-- Load More (Mock) --}}
    <div class="mt-12 flex justify-center">
        <button class="px-6 py-3 bg-white border border-stone-200 text-stone-700 rounded-xl font-semibold shadow-sm hover:bg-stone-50 hover:text-orange-600 transition-colors">
            Tampilkan Lebih Banyak
        </button>
    </div>
</x-layouts.member>
