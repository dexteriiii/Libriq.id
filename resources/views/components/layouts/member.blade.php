<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
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
    <style>
        [x-cloak] { display: none !important; }
        /* Safe area padding for mobile notches / navigation bars */
        .safe-bottom {
            padding-bottom: env(safe-area-inset-bottom, 0.5rem);
        }
    </style>
</head>
<body class="bg-stone-50 antialiased text-stone-900 flex flex-col min-h-screen" x-data="{ mobileMenuOpen: false, searchOpen: false }">
    
    {{-- Top Navigation --}}
    <header class="bg-white border-b border-stone-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                {{-- Logo & Desktop Nav --}}
                <div class="flex items-center gap-6 lg:gap-8">
                    <a href="{{ route('member.dashboard') }}" class="flex items-center gap-2.5 shrink-0">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="4" y="6" width="8" height="28" rx="2" fill="#ea580c" opacity="0.9"/>
                            <rect x="14" y="4" width="8" height="32" rx="2" fill="#ea580c"/>
                            <rect x="24" y="8" width="8" height="26" rx="2" fill="#ea580c" opacity="0.8"/>
                            <path d="M34 8 L38 6 L38 34 L34 32Z" fill="#ea580c" opacity="0.6"/>
                        </svg>
                        <span class="text-xl font-bold font-heading tracking-tight text-stone-900">Libriq<span class="text-orange-600">.id</span></span>
                    </a>
                    
                    <nav class="hidden md:flex items-center gap-1 lg:gap-2">
                        <a href="{{ route('member.dashboard') }}" class="{{ request()->routeIs('member.dashboard') ? 'bg-orange-50 text-orange-600 font-semibold' : 'text-stone-600 hover:text-stone-900 hover:bg-stone-50 font-medium' }} px-3 py-2 rounded-xl text-sm transition-colors">Beranda</a>
                        <a href="{{ route('member.catalog.index') }}" class="{{ request()->routeIs('member.catalog.*') ? 'bg-orange-50 text-orange-600 font-semibold' : 'text-stone-600 hover:text-stone-900 hover:bg-stone-50 font-medium' }} px-3 py-2 rounded-xl text-sm transition-colors">Eksplorasi</a>
                        <a href="{{ route('member.loans.history') }}" class="{{ request()->routeIs('member.loans.*') ? 'bg-orange-50 text-orange-600 font-semibold' : 'text-stone-600 hover:text-stone-900 hover:bg-stone-50 font-medium' }} px-3 py-2 rounded-xl text-sm transition-colors">Peminjaman</a>
                    </nav>
                </div>

                {{-- Search Bar (Desktop) --}}
                <div class="hidden lg:flex flex-1 max-w-md px-6">
                    <form method="GET" action="{{ route('member.catalog.index') }}" class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" name="q" value="{{ request('q') }}" class="block w-full pl-9 pr-3 py-2 border border-stone-200 rounded-full leading-5 bg-stone-50 placeholder-stone-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 text-sm transition-colors" placeholder="Cari judul, penulis, atau ISBN...">
                    </form>
                </div>

                {{-- Action Icons & Profile --}}
                <div class="flex items-center gap-1.5 sm:gap-3">
                    {{-- Mobile Search Toggle Button --}}
                    <button @click="searchOpen = !searchOpen" class="lg:hidden p-2 text-stone-500 hover:text-stone-900 rounded-full hover:bg-stone-100 transition-colors" aria-label="Cari Buku">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>

                    @php
                        $memberNotifs = \App\Models\Notification::where('user_id', auth()->id())->latest('created_at')->take(5)->get();
                        $unreadMemberNotifsCount = \App\Models\Notification::where('user_id', auth()->id())->whereNull('read_at')->count();
                    @endphp
                    <div x-data="{ notifOpen: false }" class="relative">
                        <button @click="notifOpen = !notifOpen" @click.away="notifOpen = false" class="relative p-2 text-stone-500 hover:text-stone-900 rounded-full hover:bg-stone-100 transition-colors focus:outline-none" aria-label="Notifikasi">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            @if($unreadMemberNotifsCount > 0)
                                <span class="absolute top-1 right-1 px-1.5 py-0.5 bg-red-600 text-white text-[10px] font-bold rounded-full border-2 border-white">
                                    {{ $unreadMemberNotifsCount }}
                                </span>
                            @endif
                        </button>

                        <div x-show="notifOpen" x-cloak class="absolute right-0 mt-2 w-72 sm:w-80 bg-white border border-stone-200 rounded-2xl shadow-xl py-2 z-50">
                            <div class="px-4 py-2 border-b border-stone-100 flex justify-between items-center">
                                <h4 class="font-bold text-sm text-stone-900">Notifikasi</h4>
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
                                        Tidak ada notifikasi baru.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Profile Menu (Desktop & Mobile) --}}
                    <div x-data="{ dropdownOpen: false }" class="relative">
                        <button @click="dropdownOpen = !dropdownOpen" @click.away="dropdownOpen = false" class="flex items-center gap-2 p-1 rounded-full hover:bg-stone-100 transition-colors focus:outline-none" title="Menu Pengguna">
                            <img src="{{ auth()->user()?->avatar_url }}" alt="Avatar {{ auth()->user()?->name }}" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full object-cover border border-orange-500 shadow-sm">
                        </button>
                        
                        <div x-show="dropdownOpen" x-cloak class="absolute right-0 mt-2 w-60 bg-white border border-stone-200 rounded-2xl shadow-xl py-2 z-50">
                            <div class="px-4 py-3 border-b border-stone-100 flex items-center gap-3">
                                <img src="{{ auth()->user()?->avatar_url }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover border border-orange-200 shrink-0">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-bold text-stone-900 truncate">{{ auth()->user()?->name }}</p>
                                    <p class="text-xs text-stone-500 truncate">{{ auth()->user()?->email }}</p>
                                    <p class="text-[10px] font-mono text-orange-600 font-semibold mt-0.5">ID: {{ auth()->user()?->member_id ?? 'Member' }}</p>
                                </div>
                            </div>
                            <div class="py-1 text-sm">
                                <a href="{{ route('member.profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2 text-stone-700 hover:bg-stone-50 font-medium transition-colors">
                                    <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Edit Profil
                                </a>
                                <a href="{{ route('member.loans.history') }}" class="flex items-center gap-2.5 px-4 py-2 text-stone-700 hover:bg-stone-50 font-medium transition-colors">
                                    <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    Peminjaman Saya
                                </a>
                            </div>
                            <form method="POST" action="{{ route('logout') }}" class="border-t border-stone-100 pt-1">
                                @csrf
                                <button type="submit" class="flex items-center gap-2.5 w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-medium transition-colors">
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Hamburger Button for Drawer Menu --}}
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-stone-500 hover:text-stone-900 rounded-lg hover:bg-stone-100" aria-label="Menu Utama">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>

            {{-- Expandable Mobile Search Bar --}}
            <div x-show="searchOpen" x-cloak class="lg:hidden pb-3 pt-1 border-t border-stone-100">
                <form method="GET" action="{{ route('member.catalog.index') }}" class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" name="q" value="{{ request('q') }}" class="block w-full pl-9 pr-3 py-2.5 border border-stone-200 rounded-xl leading-5 bg-stone-50 placeholder-stone-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 text-sm transition-colors" placeholder="Cari judul, penulis, atau ISBN...">
                </form>
            </div>
        </div>

        {{-- Mobile Drawer Slide-Over Menu --}}
        <div x-show="mobileMenuOpen" x-cloak class="fixed inset-0 z-50 md:hidden" role="dialog" aria-modal="true">
            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-stone-900/60 backdrop-blur-sm transition-opacity" @click="mobileMenuOpen = false"></div>

            {{-- Drawer Content --}}
            <div class="fixed inset-y-0 right-0 max-w-xs w-full bg-white shadow-2xl p-6 flex flex-col justify-between overflow-y-auto">
                <div class="space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-stone-100">
                        <div class="flex items-center gap-2">
                            <span class="text-lg font-bold font-heading text-stone-900">Menu Utama</span>
                        </div>
                        <button @click="mobileMenuOpen = false" class="p-2 text-stone-400 hover:text-stone-700 rounded-lg" aria-label="Tutup menu">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- User Profile Card --}}
                    <div class="flex items-center gap-3 p-3 bg-stone-50 rounded-xl border border-stone-200/80">
                        <img src="{{ auth()->user()?->avatar_url }}" alt="Avatar" class="w-12 h-12 rounded-full object-cover border border-orange-300 shrink-0">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-bold text-stone-900 truncate">{{ auth()->user()?->name }}</p>
                            <p class="text-xs text-stone-500 truncate">{{ auth()->user()?->email }}</p>
                            <p class="text-[11px] font-mono text-orange-600 font-semibold mt-0.5">ID: {{ auth()->user()?->member_id ?? 'Member' }}</p>
                        </div>
                    </div>

                    {{-- Navigation Links --}}
                    <nav class="space-y-1">
                        <a href="{{ route('member.dashboard') }}" class="{{ request()->routeIs('member.dashboard') ? 'bg-orange-50 text-orange-600 font-semibold' : 'text-stone-700 hover:bg-stone-50 font-medium' }} flex items-center gap-3 px-3 py-3 rounded-xl text-sm transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Beranda
                        </a>
                        <a href="{{ route('member.catalog.index') }}" class="{{ request()->routeIs('member.catalog.*') ? 'bg-orange-50 text-orange-600 font-semibold' : 'text-stone-700 hover:bg-stone-50 font-medium' }} flex items-center gap-3 px-3 py-3 rounded-xl text-sm transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            Eksplorasi Katalog
                        </a>
                        <a href="{{ route('member.loans.history') }}" class="{{ request()->routeIs('member.loans.*') ? 'bg-orange-50 text-orange-600 font-semibold' : 'text-stone-700 hover:bg-stone-50 font-medium' }} flex items-center gap-3 px-3 py-3 rounded-xl text-sm transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            Peminjaman Saya
                        </a>
                        <a href="{{ route('member.profile.edit') }}" class="{{ request()->routeIs('member.profile.*') ? 'bg-orange-50 text-orange-600 font-semibold' : 'text-stone-700 hover:bg-stone-50 font-medium' }} flex items-center gap-3 px-3 py-3 rounded-xl text-sm transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Edit Profil
                        </a>
                    </nav>
                </div>

                {{-- Logout Button in Drawer --}}
                <div class="pt-6 border-t border-stone-100">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-red-50 text-red-600 rounded-xl font-semibold text-sm hover:bg-red-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Keluar dari Akun
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    {{-- Main Content (with extra bottom padding for mobile navigation bar) --}}
    <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 sm:py-8 pb-24 md:pb-8">
        {{ $slot }}
    </main>

    {{-- Mobile Bottom Navigation Bar (Android/iOS style) --}}
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-stone-200 safe-bottom shadow-lg">
        <div class="grid grid-cols-4 h-16 max-w-md mx-auto">
            <a href="{{ route('member.dashboard') }}" class="flex flex-col items-center justify-center gap-1 {{ request()->routeIs('member.dashboard') ? 'text-orange-600 font-bold' : 'text-stone-500 hover:text-stone-900 font-medium' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ request()->routeIs('member.dashboard') ? '2.5' : '2' }}" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="text-[10px]">Beranda</span>
            </a>
            <a href="{{ route('member.catalog.index') }}" class="flex flex-col items-center justify-center gap-1 {{ request()->routeIs('member.catalog.*') ? 'text-orange-600 font-bold' : 'text-stone-500 hover:text-stone-900 font-medium' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ request()->routeIs('member.catalog.*') ? '2.5' : '2' }}" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <span class="text-[10px]">Katalog</span>
            </a>
            <a href="{{ route('member.loans.history') }}" class="flex flex-col items-center justify-center gap-1 {{ request()->routeIs('member.loans.*') ? 'text-orange-600 font-bold' : 'text-stone-500 hover:text-stone-900 font-medium' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ request()->routeIs('member.loans.*') ? '2.5' : '2' }}" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span class="text-[10px]">Pinjaman</span>
            </a>
            <a href="{{ route('member.profile.edit') }}" class="flex flex-col items-center justify-center gap-1 {{ request()->routeIs('member.profile.*') ? 'text-orange-600 font-bold' : 'text-stone-500 hover:text-stone-900 font-medium' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ request()->routeIs('member.profile.*') ? '2.5' : '2' }}" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span class="text-[10px]">Profil</span>
            </a>
        </div>
    </nav>

    {{-- Footer (Desktop only or below content) --}}
    <footer class="bg-white border-t border-stone-200 mt-auto hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <p class="text-center text-sm text-stone-500">
                &copy; {{ date('Y') }} Libriq.id. Sistem Manajemen Perpustakaan Digital.
            </p>
        </div>
    </footer>
</body>
</html>
