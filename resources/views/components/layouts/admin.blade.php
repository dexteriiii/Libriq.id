<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Dashboard — Libriq.id' }}</title>

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
<body class="bg-stone-50 antialiased text-stone-900" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">
        
        {{-- Mobile sidebar backdrop --}}
        <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40 bg-stone-900/50 lg:hidden" @click="sidebarOpen = false"></div>

        {{-- Sidebar --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-stone-200 flex flex-col transition-transform duration-300 lg:static lg:translate-x-0">
            {{-- Logo --}}
            <div class="h-16 flex items-center px-6 border-b border-stone-200 shrink-0">
                <div class="flex items-center gap-2.5">
                    <svg class="w-7 h-7" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="4" y="6" width="8" height="28" rx="2" fill="#ea580c" opacity="0.9"/>
                        <rect x="14" y="4" width="8" height="32" rx="2" fill="#ea580c"/>
                        <rect x="24" y="8" width="8" height="26" rx="2" fill="#ea580c" opacity="0.8"/>
                        <path d="M34 8 L38 6 L38 34 L34 32Z" fill="#ea580c" opacity="0.6"/>
                    </svg>
                    <span class="text-xl font-bold font-heading tracking-tight">Libriq.id</span>
                </div>
            </div>

            {{-- Nav Links --}}
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'bg-orange-50 text-orange-700' : 'text-stone-600 hover:bg-stone-50 hover:text-stone-900' }} flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-colors">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-orange-600' : 'text-stone-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.books.index') }}" class="{{ request()->routeIs('admin.books.*') ? 'bg-orange-50 text-orange-700' : 'text-stone-600 hover:bg-stone-50 hover:text-stone-900' }} flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-colors">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.books.*') ? 'text-orange-600' : 'text-stone-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Katalog Buku
                </a>
                <a href="{{ route('admin.loans.index') }}" class="{{ request()->routeIs('admin.loans.*') ? 'bg-orange-50 text-orange-700' : 'text-stone-600 hover:bg-stone-50 hover:text-stone-900' }} flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-colors">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.loans.*') ? 'text-orange-600' : 'text-stone-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    Sirkulasi
                </a>
                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'bg-orange-50 text-orange-700' : 'text-stone-600 hover:bg-stone-50 hover:text-stone-900' }} flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-colors">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.users.*') ? 'text-orange-600' : 'text-stone-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Anggota
                </a>
                <a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'bg-orange-50 text-orange-700' : 'text-stone-600 hover:bg-stone-50 hover:text-stone-900' }} flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-colors">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.settings.*') ? 'text-orange-600' : 'text-stone-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Pengaturan
                </a>
            </nav>

            {{-- Logout / User Profile --}}
            <div class="p-4 border-t border-stone-200">
                <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-stone-50 transition-colors">
                    <img src="{{ auth()->user()?->avatar_url }}" alt="Avatar" class="w-9 h-9 rounded-full object-cover border border-stone-200 shrink-0">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-stone-900 truncate">{{ auth()->user()?->name }}</p>
                        <p class="text-xs text-stone-500 truncate">{{ auth()->user()?->email }}</p>
                    </div>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg font-medium transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Wrapper --}}
        <div class="flex-1 flex flex-col h-screen overflow-hidden">
            {{-- Topbar --}}
            <header class="h-16 bg-white border-b border-stone-200 flex items-center justify-between px-4 sm:px-6 shrink-0">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 text-stone-500 hover:text-stone-900 rounded-lg focus:bg-stone-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
                <div class="flex items-center gap-4">
                    @php
                        $adminNotifs = \App\Models\Notification::where('user_id', auth()->id())->latest('created_at')->take(5)->get();
                        $unreadAdminNotifsCount = \App\Models\Notification::where('user_id', auth()->id())->whereNull('read_at')->count();
                    @endphp
                    <div x-data="{ notifOpen: false }" class="relative">
                        <button @click="notifOpen = !notifOpen" @click.away="notifOpen = false" class="relative p-2 text-stone-500 hover:text-stone-900 rounded-full hover:bg-stone-100 transition-colors focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            @if($unreadAdminNotifsCount > 0)
                                <span class="absolute top-1 right-1 px-1.5 py-0.5 bg-red-600 text-white text-[10px] font-bold rounded-full border-2 border-white">
                                    {{ $unreadAdminNotifsCount }}
                                </span>
                            @endif
                        </button>

                        {{-- Dropdown Notifications --}}
                        <div x-show="notifOpen" x-cloak class="absolute right-0 mt-2 w-80 bg-white border border-stone-200 rounded-2xl shadow-xl py-2 z-50">
                            <div class="px-4 py-2 border-b border-stone-100 flex justify-between items-center">
                                <h4 class="font-bold text-sm text-stone-900">Notifikasi Admin</h4>
                                <span class="text-xs text-orange-600 font-semibold">{{ $unreadAdminNotifsCount }} belum dibaca</span>
                            </div>
                            <div class="max-h-72 overflow-y-auto divide-y divide-stone-100 text-xs">
                                @forelse($adminNotifs as $notif)
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

                    {{-- Admin Profile Link / Avatar Topbar --}}
                    <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-2 p-1 rounded-full hover:bg-stone-100 transition-colors" title="Edit Profil Admin">
                        <img src="{{ auth()->user()?->avatar_url }}" alt="Avatar" class="w-9 h-9 rounded-full object-cover border border-orange-500 shadow-sm">
                    </a>
                </div>
            </header>

            {{-- Main Content --}}
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
