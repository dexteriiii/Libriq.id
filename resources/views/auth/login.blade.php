<x-layouts.guest :title="'Masuk — Libriq.id'">
    <div class="min-h-screen flex flex-col lg:flex-row bg-stone-50">

        {{-- Left Panel — Branding --}}
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-orange-600 to-orange-700 items-center justify-center p-12">
            {{-- Decorative elements --}}
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-16 left-16 w-32 h-40 border-2 border-white rounded-lg rotate-[-15deg]"></div>
                <div class="absolute top-40 right-24 w-24 h-32 border-2 border-white rounded-lg rotate-[10deg]"></div>
                <div class="absolute bottom-32 left-32 w-28 h-36 border-2 border-white rounded-lg rotate-[5deg]"></div>
                <div class="absolute bottom-20 right-40 w-20 h-28 border-2 border-white rounded-lg rotate-[-8deg]"></div>
                <div class="absolute top-1/3 left-1/2 w-16 h-20 border-2 border-white rounded-lg rotate-[20deg]"></div>
            </div>

            {{-- Floating dots --}}
            <div class="absolute top-24 right-32 w-2 h-2 bg-white/40 rounded-full"></div>
            <div class="absolute top-48 left-48 w-3 h-3 bg-white/30 rounded-full"></div>
            <div class="absolute bottom-40 right-56 w-2 h-2 bg-white/50 rounded-full"></div>
            <div class="absolute bottom-60 left-20 w-2.5 h-2.5 bg-white/35 rounded-full"></div>

            <div class="relative z-10 text-white max-w-lg">
                {{-- Logo --}}
                <div class="flex items-center gap-3 mb-8">
                    <svg class="w-10 h-10" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="4" y="6" width="8" height="28" rx="2" fill="white" opacity="0.9"/>
                        <rect x="14" y="4" width="8" height="32" rx="2" fill="white"/>
                        <rect x="24" y="8" width="8" height="26" rx="2" fill="white" opacity="0.8"/>
                        <path d="M34 8 L38 6 L38 34 L34 32Z" fill="white" opacity="0.6"/>
                    </svg>
                    <span class="text-2xl font-bold font-heading tracking-tight">Libriq.id</span>
                </div>

                <h1 class="text-4xl xl:text-5xl font-extrabold font-heading leading-tight mb-6">
                    Kelola Perpustakaan<br>Digital Anda
                </h1>
                <p class="text-lg text-orange-100 leading-relaxed">
                    Digitalisasi sirkulasi buku, otomasi denda keterlambatan, dan kelola inventaris 
                    perpustakaan dengan mudah dalam satu platform terintegrasi.
                </p>

                {{-- Stats preview --}}
                <div class="mt-10 flex gap-8">
                    <div>
                        <div class="text-3xl font-bold font-heading">10K+</div>
                        <div class="text-sm text-orange-200">Koleksi Buku</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold font-heading">5K+</div>
                        <div class="text-sm text-orange-200">Anggota Aktif</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold font-heading">99%</div>
                        <div class="text-sm text-orange-200">Uptime</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Panel — Login Form --}}
        <div class="flex-1 flex items-center justify-center p-6 sm:p-8 lg:p-12">
            <div class="w-full max-w-md">

                {{-- Mobile logo (shown only on small screens) --}}
                <div class="lg:hidden flex items-center justify-center gap-3 mb-8">
                    <svg class="w-9 h-9" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="4" y="6" width="8" height="28" rx="2" fill="#ea580c" opacity="0.9"/>
                        <rect x="14" y="4" width="8" height="32" rx="2" fill="#ea580c"/>
                        <rect x="24" y="8" width="8" height="26" rx="2" fill="#ea580c" opacity="0.8"/>
                        <path d="M34 8 L38 6 L38 34 L34 32Z" fill="#ea580c" opacity="0.6"/>
                    </svg>
                    <span class="text-2xl font-bold font-heading text-stone-900 tracking-tight">Libriq.id</span>
                </div>

                {{-- Form Card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-stone-200 p-8 sm:p-10">
                    {{-- Desktop logo inside card --}}
                    <div class="hidden lg:flex items-center justify-center gap-2.5 mb-6">
                        <svg class="w-8 h-8" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="4" y="6" width="8" height="28" rx="2" fill="#ea580c" opacity="0.9"/>
                            <rect x="14" y="4" width="8" height="32" rx="2" fill="#ea580c"/>
                            <rect x="24" y="8" width="8" height="26" rx="2" fill="#ea580c" opacity="0.8"/>
                            <path d="M34 8 L38 6 L38 34 L34 32Z" fill="#ea580c" opacity="0.6"/>
                        </svg>
                        <span class="text-xl font-bold font-heading text-stone-900 tracking-tight">Libriq.id</span>
                    </div>

                    <h2 class="text-2xl font-bold font-heading text-stone-900 text-center">Selamat Datang!</h2>
                    <p class="text-stone-500 text-center mt-2 mb-8">Masuk ke akun perpustakaan Anda</p>

                    {{-- Error Messages --}}
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ $errors->first() }}</span>
                            </div>
                        </div>
                    @endif

                    {{-- Session Status (e.g. password reset) --}}
                    @if (session('status'))
                        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ session('status') }}</span>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-stone-700 mb-1.5">
                                Email
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    autocomplete="email"
                                    placeholder="nama@email.com"
                                    class="block w-full pl-11 pr-4 py-3 bg-stone-50 border border-stone-200 rounded-xl text-stone-900 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition duration-200 @error('email') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                                >
                            </div>
                        </div>

                        {{-- Password --}}
                        <div x-data="{ showPassword: false }">
                            <label for="password" class="block text-sm font-medium text-stone-700 mb-1.5">
                                Password
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <input
                                    id="password"
                                    name="password"
                                    :type="showPassword ? 'text' : 'password'"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Masukkan password"
                                    class="block w-full pl-11 pr-12 py-3 bg-stone-50 border border-stone-200 rounded-xl text-stone-900 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition duration-200 @error('password') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                                >
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-stone-400 hover:text-stone-600 transition"
                                >
                                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Remember Me & Forgot Password --}}
                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    class="w-4 h-4 rounded border-stone-300 text-orange-600 focus:ring-orange-500/20 focus:ring-offset-0 transition"
                                >
                                <span class="text-sm text-stone-600">Ingat saya</span>
                            </label>
                            {{-- <a href="#" class="text-sm text-orange-600 hover:text-orange-700 font-medium transition">
                                Lupa password?
                            </a> --}}
                        </div>

                        {{-- Submit Button --}}
                        <button
                            type="submit"
                            class="w-full py-3 px-4 bg-orange-600 hover:bg-orange-700 active:bg-orange-800 text-white font-semibold rounded-xl shadow-sm hover:shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-orange-500/40 focus:ring-offset-2"
                        >
                            Masuk
                        </button>
                    </form>

                    {{-- Register Link --}}
                    <p class="text-center text-sm text-stone-500 mt-6">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-orange-600 hover:text-orange-700 font-semibold transition">
                            Daftar sekarang
                        </a>
                    </p>
                </div>

                {{-- Footer --}}
                <p class="text-center text-xs text-stone-400 mt-6">
                    &copy; {{ date('Y') }} Libriq.id — Sistem Manajemen Perpustakaan Digital
                </p>
            </div>
        </div>
    </div>
</x-layouts.guest>
