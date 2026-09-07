<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Libriq.id — Perpustakaan Digital' }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    {{-- Tailwind CSS v4 (CDN) --}}
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        @theme {
            --font-sans: 'Inter', ui-sans-serif, system-ui, sans-serif;
            --font-heading: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
            --font-mono: 'JetBrains Mono', ui-monospace, monospace;
        }
    </style>

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-stone-50 antialiased text-stone-900 flex flex-col min-h-screen">
    
    {{-- Top Navigation --}}
    <header class="bg-white border-b border-stone-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                {{-- Logo & Desktop Nav --}}
                <div class="flex items-center gap-8">
                    <a href="{{ route('member.catalog') ?? '#' }}" class="flex items-center gap-2.5">
                        <svg class="w-7 h-7" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="4" y="6" width="8" height="28" rx="2" fill="#ea580c" opacity="0.9"/>
                            <rect x="14" y="4" width="8" height="32" rx="2" fill="#ea580c"/>
                            <rect x="24" y="8" width="8" height="26" rx="2" fill="#ea580c" opacity="0.8"/>
                            <path d="M34 8 L38 6 L38 34 L34 32Z" fill="#ea580c" opacity="0.6"/>
                        </svg>
                        <span class="text-xl font-bold font-heading tracking-tight">Libriq.id</span>
                    </a>
                    
                    <nav class="hidden md:flex items-center gap-6">
                        <a href="{{ route('member.catalog') ?? '#' }}" class="{{ request()->routeIs('member.catalog') ? 'text-orange-600 font-semibold' : 'text-stone-600 hover:text-stone-900 font-medium' }} transition-colors">Eksplorasi</a>
                        <a href="#" class="text-stone-600 hover:text-stone-900 font-medium transition-colors">Pinjamanku</a>
                        <a href="#" class="text-stone-600 hover:text-stone-900 font-medium transition-colors">Riwayat</a>
                    </nav>
                </div>

                {{-- Search Bar (Desktop) --}}
                <div class="hidden lg:flex flex-1 max-w-lg px-8">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" class="block w-full pl-10 pr-3 py-2 border border-stone-200 rounded-full leading-5 bg-stone-50 placeholder-stone-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 sm:text-sm transition-colors" placeholder="Cari judul buku, penulis, atau topik...">
                    </div>
                </div>

                {{-- User Profile & Mobile Menu --}}
                <div class="flex items-center gap-4">
                    <button class="lg:hidden p-2 text-stone-500 hover:text-stone-900 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                    
                    <div x-data="{ dropdownOpen: false }" class="relative">
                        <button @click="dropdownOpen = !dropdownOpen" @click.away="dropdownOpen = false" class="flex items-center gap-2 p-1 rounded-full hover:bg-stone-100 transition-colors focus:outline-none">
                            <div class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-sm">
                                ME
                            </div>
                        </button>
                        
                        {{-- Dropdown --}}
                        <div x-show="dropdownOpen" x-cloak class="absolute right-0 mt-2 w-48 bg-white border border-stone-200 rounded-xl shadow-lg py-1 z-50">
                            <div class="px-4 py-2 border-b border-stone-100">
                                <p class="text-sm font-medium text-stone-900">Member User</p>
                                <p class="text-xs text-stone-500 truncate">member@libriq.id</p>
                            </div>
                            <a href="#" class="block px-4 py-2 text-sm text-stone-700 hover:bg-stone-50">Profil Saya</a>
                            <a href="#" class="block px-4 py-2 text-sm text-stone-700 hover:bg-stone-50">Denda & Tagihan</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="bg-white border-t border-stone-200 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <p class="text-center text-sm text-stone-500">
                &copy; {{ date('Y') }} Libriq.id. Sistem Manajemen Perpustakaan Digital.
            </p>
        </div>
    </footer>
</body>
</html>
