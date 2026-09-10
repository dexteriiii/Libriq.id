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
<body class="bg-stone-50 antialiased text-stone-900 flex flex-col min-h-screen" x-data="{ mobileMenuOpen: false }">
    
    {{-- Top Navigation --}}
    <header class="bg-white border-b border-stone-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                {{-- Logo & Desktop Nav --}}
                <div class="flex items-center gap-8">
                    <a href="{{ route('member.dashboard') }}" class="flex items-center gap-2.5">
                        <svg class="w-7 h-7" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="4" y="6" width="8" height="28" rx="2" fill="#ea580c" opacity="0.9"/>
                            <rect x="14" y="4" width="8" height="32" rx="2" fill="#ea580c"/>
                            <rect x="24" y="8" width="8" height="26" rx="2" fill="#ea580c" opacity="0.8"/>
                            <path d="M34 8 L38 6 L38 34 L34 32Z" fill="#ea580c" opacity="0.6"/>
                        </svg>
                        <span class="text-xl font-bold font-heading tracking-tight">Libriq.id</span>
                    </a>
                    
                    <nav class="hidden md:flex items-center gap-6">
                        <a href="{{ route('member.dashboard') }}" class="{{ request()->routeIs('member.dashboard') ? 'text-orange-600 font-semibold' : 'text-stone-600 hover:text-stone-900 font-medium' }} transition-colors">Beranda</a>
                        <a href="{{ route('member.catalog.index') }}" class="{{ request()->routeIs('member.catalog.*') ? 'text-orange-600 font-semibold' : 'text-stone-600 hover:text-stone-900 font-medium' }} transition-colors">Eksplorasi Katalog</a>
                        <a href="{{ route('member.loans.history') }}" class="{{ request()->routeIs('member.loans.*') ? 'text-orange-600 font-semibold' : 'text-stone-600 hover:text-stone-900 font-medium' }} transition-colors">Peminjaman Saya</a>
                    </nav>
                </div>

                {{-- Search Bar (Desktop) --}}
                <div class="hidden lg:flex flex-1 max-w-lg px-8">
                    <form method="GET" action="{{ route('member.catalog.index') }}" class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" name="q" value="{{ request('q') }}" class="block w-full pl-10 pr-3 py-2 border border-stone-200 rounded-full leading-5 bg-stone-50 placeholder-stone-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 sm:text-sm transition-colors" placeholder="Cari judul buku, penulis, atau ISBN...">
                    </form>
                </div>

                {{-- User Profile & Mobile Hamburger --}}
                <div class="flex items-center gap-3">
                    @php
                        $memberNotifs = \App\Models\Notification::where('user_id', auth()->id())->latest('created_at')->take(5)->get();
                        $unreadMemberNotifsCount = \App\Models\Notification::where('user_id', auth()->id())->whereNull('read_at')->count();
                    @endphp
                    <div x-data="{ notifOpen: false }" class="relative">
                        <button @click="notifOpen = !notifOpen" @click.away="notifOpen = false" class="relative p-2 text-stone-500 hover:text-stone-900 rounded-full hover:bg-stone-100 transition-colors focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            @if($unreadMemberNotifsCount > 0)
                                <span class="absolute top-1 right-1 px-1.5 py-0.5 bg-red-600 text-white text-[10px] font-bold rounded-full border-2 border-white">
                                    {{ $unreadMemberNotifsCount }}
                                </span>
                            @endif
                        </button>

                        <div x-show="notifOpen" x-cloak class="absolute right-0 mt-2 w-80 bg-white border border-stone-200 rounded-2xl shadow-xl py-2 z-50">
                            <div class="px-4 py-2 border-b border-stone-100 flex justify-between items-center">
                                <h4 class="font-bold text-sm text-stone-900">Notifikasi Saya</h4>
                                <span class="text-xs text-orange-600 font-semibold">{{ $unreadMemberNotifsCount }} belum dibaca</span>
                            </div>
                            <div class="max-h-72 overflow-y-auto divide-y divide-stone-100 text-xs">
                                @forelse($memberNotifs as $notif)
                                    <div class="px-4 py-3 hover:bg-stone-50 transition-colors">
                                        <p class="text-stone-800 font-medium leading-tight">{{ $notif->message }}</p>
                                        <p class="text-stone-400 text-[10px] mt-1">{{ $notif->created_at ? $notif->created_at->diffForHumans() : 'Baru saja' }}</p>
                                    </div>
                                @empty
                                    <div class="px-4 py-6 text-center text-stone-400">
                                        Tidak ada notifikasi.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-stone-500 hover:text-stone-900 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>

                    <div x-data="{ dropdownOpen: false }" class="relative">
                        <button @click="dropdownOpen = !dropdownOpen" @click.away="dropdownOpen = false" class="flex items-center gap-2 p-1 rounded-full hover:bg-stone-100 transition-colors focus:outline-none">
                            <div class="w-9 h-9 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-sm">
                                {{ strtoupper(substr(auth()->user()?->name ?? 'M', 0, 2)) }}
                            </div>
                        </button>
                        
                        {{-- Dropdown --}}
                        <div x-show="dropdownOpen" x-cloak class="absolute right-0 mt-2 w-56 bg-white border border-stone-200 rounded-xl shadow-lg py-1 z-50">
                            <div class="px-4 py-3 border-b border-stone-100">
                                <p class="text-sm font-semibold text-stone-900">{{ auth()->user()?->name }}</p>
                                <p class="text-xs text-stone-500 truncate">{{ auth()->user()?->email }}</p>
                                <p class="text-[10px] font-mono text-orange-600 mt-1">ID: {{ auth()->user()?->member_id ?? 'Member' }}</p>
                            </div>
                            <a href="{{ route('member.profile.edit') }}" class="block px-4 py-2 text-sm text-stone-700 hover:bg-stone-50 font-medium">Edit Profil Saya</a>
                            <form method="POST" action="{{ route('logout') }}" class="border-t border-stone-100">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-medium">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Mobile Navigation Dropdown --}}
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden border-t border-stone-200 bg-white px-4 py-3 space-y-2">
            <a href="{{ route('member.dashboard') }}" class="block px-3 py-2 rounded-lg font-medium text-stone-700 hover:bg-stone-50">Beranda</a>
            <a href="{{ route('member.catalog.index') }}" class="block px-3 py-2 rounded-lg font-medium text-stone-700 hover:bg-stone-50">Eksplorasi Katalog</a>
            <a href="{{ route('member.loans.history') }}" class="block px-3 py-2 rounded-lg font-medium text-stone-700 hover:bg-stone-50">Peminjaman Saya</a>
            <a href="{{ route('member.profile.edit') }}" class="block px-3 py-2 rounded-lg font-medium text-stone-700 hover:bg-stone-50">Edit Profil</a>
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
