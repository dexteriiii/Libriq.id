<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — Perpustakaan Digital</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui; }
        .font-heading { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui; }
        .font-mono-code { font-family: 'JetBrains Mono', ui-monospace, monospace; }
    </style>
</head>
<body class="bg-stone-50 text-stone-900 antialiased" x-data="{ mobileNavOpen: false }">

    <div class="min-h-screen flex flex-col">
        {{-- Top Navigation --}}
        <header class="bg-white border-b border-stone-200 sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center gap-8">
                        <a href="{{ route('member.dashboard') }}" class="font-heading font-bold text-lg text-stone-900">
                            Pustaka<span class="text-orange-600">Digital</span>
                        </a>

                        <nav class="hidden md:flex items-center gap-1">
                            @php
                                $navItems = [
                                    ['route' => 'member.dashboard', 'label' => 'Beranda'],
                                    ['route' => 'member.catalog.index', 'label' => 'Katalog'],
                                    ['route' => 'member.loans.history', 'label' => 'Peminjaman Saya'],
                                ];
                            @endphp
                            @foreach ($navItems as $item)
                                <a href="{{ route($item['route']) }}"
                                   class="px-3 py-2 rounded-md text-sm font-medium transition-colors
                                          {{ request()->routeIs($item['route'].'*')
                                                ? 'bg-orange-50 text-orange-600'
                                                : 'text-stone-500 hover:text-stone-900 hover:bg-stone-50' }}">
                                    {{ $item['label'] }}
                                </a>
                            @endforeach
                        </nav>
                    </div>

                    <div class="hidden md:flex items-center gap-4">
                        <span class="text-sm text-stone-500">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-stone-500 hover:text-red-600 transition-colors">
                                Keluar
                            </button>
                        </form>
                    </div>

                    <button class="md:hidden text-stone-500" @click="mobileNavOpen = !mobileNavOpen" aria-label="Buka menu">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Mobile Nav --}}
            <div class="md:hidden border-t border-stone-200" x-show="mobileNavOpen" x-cloak>
                <nav class="px-4 py-3 space-y-1">
                    <a href="{{ route('member.dashboard') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('member.dashboard') ? 'bg-orange-50 text-orange-600' : 'text-stone-600' }}">Beranda</a>
                    <a href="{{ route('member.catalog.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('member.catalog.*') ? 'bg-orange-50 text-orange-600' : 'text-stone-600' }}">Katalog</a>
                    <a href="{{ route('member.loans.history') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('member.loans.*') ? 'bg-orange-50 text-orange-600' : 'text-stone-600' }}">Peminjaman Saya</a>
                    <form method="POST" action="{{ route('logout') }}" class="px-3 py-2">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-red-600">Keluar</button>
                    </form>
                </nav>
            </div>
        </header>

        {{-- Flash Messages --}}
        <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 mt-4">
            @if (session('success'))
                <div class="rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 mb-4">
                    {{ session('error') }}
                </div>
            @endif
        </div>

        {{-- Main Content --}}
        <main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6">
            @yield('content')
        </main>

        <footer class="border-t border-stone-200 py-6">
            <p class="text-center text-xs text-stone-400">© {{ date('Y') }} Pustaka Digital — Sistem Manajemen Perpustakaan</p>
        </footer>
    </div>

</body>
</html>