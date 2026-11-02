<x-layouts.guest :title="'Aktivasi Akun Siswa Baru — Libriq.id'">
    <div class="min-h-screen flex flex-col lg:flex-row bg-stone-50">

        {{-- Left Panel — Branding --}}
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-orange-600 to-orange-700 items-center justify-center p-12">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-16 left-16 w-32 h-40 border-2 border-white rounded-lg rotate-[-15deg]"></div>
                <div class="absolute top-40 right-24 w-24 h-32 border-2 border-white rounded-lg rotate-[10deg]"></div>
                <div class="absolute bottom-32 left-32 w-28 h-36 border-2 border-white rounded-lg rotate-[5deg]"></div>
                <div class="absolute bottom-20 right-40 w-20 h-28 border-2 border-white rounded-lg rotate-[-8deg]"></div>
            </div>

            <div class="relative z-10 text-white max-w-lg">
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
                    Aktivasi Akun<br>Siswa Baru
                </h1>
                <p class="text-lg text-orange-100 leading-relaxed">
                    Siswa yang datanya telah diimpor oleh pihak sekolah/admin dapat langsung mengaktifkan akun di sini. 
                    Cukup verifikasi NIS dan nama Anda, lalu buat password untuk mulai meminjam buku.
                </p>

                <div class="mt-10 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center font-bold text-sm shrink-0">1</div>
                        <div class="text-sm text-orange-100">Verifikasi NIS & Nama Lengkap yang terdaftar</div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center font-bold text-sm shrink-0">2</div>
                        <div class="text-sm text-orange-100">Lengkapi email pribadi dan buat password baru</div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center font-bold text-sm shrink-0">3</div>
                        <div class="text-sm text-orange-100">Akun langsung aktif dan siap digunakan membaca</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Panel — Form Card --}}
        <div class="flex-1 flex items-center justify-center p-4 sm:p-8 lg:p-12">
            <div class="w-full max-w-md">

                {{-- Mobile logo --}}
                <div class="lg:hidden flex items-center justify-center gap-3 mb-6">
                    <svg class="w-9 h-9" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="4" y="6" width="8" height="28" rx="2" fill="#ea580c" opacity="0.9"/>
                        <rect x="14" y="4" width="8" height="32" rx="2" fill="#ea580c"/>
                        <rect x="24" y="8" width="8" height="26" rx="2" fill="#ea580c" opacity="0.8"/>
                        <path d="M34 8 L38 6 L38 34 L34 32Z" fill="#ea580c" opacity="0.6"/>
                    </svg>
                    <span class="text-2xl font-bold font-heading text-stone-900 tracking-tight">Libriq.id</span>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-stone-200 p-5 sm:p-10">

                    {{-- Header --}}
                    <div class="text-center mb-6">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800 mb-2">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Aktivasi Pertama Kali
                        </span>
                        <h2 class="text-2xl font-bold font-heading text-stone-900">
                            {{ $student ? 'Buat Password Akun' : 'Verifikasi Data Siswa' }}
                        </h2>
                        <p class="text-stone-500 text-xs sm:text-sm mt-1">
                            {{ $student ? 'Lengkapi email dan buat password akun Anda' : 'Masukkan NIS dan Nama Lengkap yang telah didaftarkan sekolah' }}
                        </p>
                    </div>

                    {{-- Feedback Alerts --}}
                    @if (session('status'))
                        <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (! $student)
                        {{-- STEP 1: VERIFIKASI NIS & NAMA --}}
                        <form method="POST" action="{{ route('activate.verify') }}" class="space-y-4">
                            @csrf

                            <div>
                                <label for="nis" class="block text-sm font-medium text-stone-700 mb-1.5">
                                    NIS (Nomor Induk Siswa) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                                    </div>
                                    <input 
                                        id="nis" 
                                        name="nis" 
                                        type="text" 
                                        value="{{ old('nis') }}" 
                                        required 
                                        autofocus
                                        autocomplete="off"
                                        placeholder="Contoh: 10003"
                                        class="block w-full pl-11 pr-4 py-3 bg-stone-50 border border-stone-200 rounded-xl text-stone-900 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-mono transition"
                                    >
                                </div>
                            </div>

                            <div>
                                <label for="name" class="block text-sm font-medium text-stone-700 mb-1.5">
                                    Nama Lengkap Siswa <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </div>
                                    <input 
                                        id="name" 
                                        name="name" 
                                        type="text" 
                                        value="{{ old('name') }}" 
                                        required 
                                        autocomplete="name"
                                        placeholder="Sesuai nama yang didaftarkan pihak sekolah"
                                        class="block w-full pl-11 pr-4 py-3 bg-stone-50 border border-stone-200 rounded-xl text-stone-900 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition"
                                    >
                                </div>
                                <p class="text-xs text-stone-400 mt-1">Huruf besar/kecil tidak berpengaruh, namun ejaan nama harus tepat.</p>
                            </div>

                            <button 
                                type="submit" 
                                class="w-full mt-2 py-3 px-4 bg-orange-600 hover:bg-orange-700 active:bg-orange-800 text-white font-semibold rounded-xl shadow-sm hover:shadow transition-all duration-200 flex items-center justify-center gap-2"
                            >
                                <span>Verifikasi Data Siswa</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </form>
                    @else
                        {{-- STEP 2: LENGKAPI EMAIL & BUAT PASSWORD --}}
                        <div class="mb-5 p-3.5 bg-orange-50 border border-orange-200 rounded-xl">
                            <div class="text-xs font-semibold text-orange-800 mb-1 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Data Siswa Terverifikasi:
                            </div>
                            <div class="text-xs text-stone-700 space-y-0.5">
                                <div>Nama: <strong class="text-stone-900">{{ $student->name }}</strong></div>
                                <div>NIS: <strong class="font-mono text-stone-900">{{ $student->nis }}</strong> @if($student->kelas) &bull; Kelas: <strong class="text-stone-900">{{ $student->kelas }}</strong> @endif</div>
                            </div>
                            <form method="POST" action="{{ route('activate.cancel') }}" class="mt-2 pt-2 border-t border-orange-200/60">
                                @csrf
                                <button type="submit" class="text-[11px] text-orange-700 hover:text-orange-900 underline font-medium">
                                    &larr; Bukan akun Anda? Verifikasi ulang
                                </button>
                            </form>
                        </div>

                        <form method="POST" action="{{ route('activate.complete') }}" class="space-y-4" x-data="{ showPass: false }">
                            @csrf

                            <div>
                                <label for="email" class="block text-sm font-medium text-stone-700 mb-1.5">
                                    Alamat Email Anda <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </div>
                                    <input 
                                        id="email" 
                                        name="email" 
                                        type="email" 
                                        value="{{ old('email', $student->email) }}" 
                                        required 
                                        autofocus
                                        autocomplete="email"
                                        placeholder="nama@email.com"
                                        class="block w-full pl-11 pr-4 py-3 bg-stone-50 border border-stone-200 rounded-xl text-stone-900 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition"
                                    >
                                </div>
                                <p class="text-xs text-stone-400 mt-1">Dapat digunakan sebagai alternatif login & notifikasi peminjaman.</p>
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-stone-700 mb-1.5">
                                    Buat Password Baru <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    </div>
                                    <input 
                                        id="password" 
                                        name="password" 
                                        :type="showPass ? 'text' : 'password'"
                                        required 
                                        autocomplete="new-password"
                                        placeholder="Minimal 8 karakter"
                                        class="block w-full pl-11 pr-11 py-3 bg-stone-50 border border-stone-200 rounded-xl text-stone-900 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition"
                                    >
                                    <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-stone-400 hover:text-stone-600">
                                        <svg x-show="!showPass" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <svg x-show="showPass" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-stone-700 mb-1.5">
                                    Konfirmasi Password Baru <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    </div>
                                    <input 
                                        id="password_confirmation" 
                                        name="password_confirmation" 
                                        :type="showPass ? 'text' : 'password'"
                                        required 
                                        autocomplete="new-password"
                                        placeholder="Ketik ulang password baru Anda"
                                        class="block w-full pl-11 pr-4 py-3 bg-stone-50 border border-stone-200 rounded-xl text-stone-900 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition"
                                    >
                                </div>
                            </div>

                            <button 
                                type="submit" 
                                class="w-full mt-2 py-3 px-4 bg-orange-600 hover:bg-orange-700 active:bg-orange-800 text-white font-semibold rounded-xl shadow-sm hover:shadow transition-all duration-200 flex items-center justify-center gap-2"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Aktifkan Akun & Masuk Sekarang</span>
                            </button>
                        </form>
                    @endif

                    {{-- Back to login link --}}
                    <div class="mt-6 pt-5 border-t border-stone-100 text-center">
                        <a href="{{ route('login') }}" class="text-xs text-stone-500 hover:text-stone-800 font-medium inline-flex items-center gap-1.5 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Kembali ke Halaman Login
                        </a>
                    </div>
                </div>

                <p class="text-center text-xs text-stone-400 mt-6">
                    &copy; {{ date('Y') }} Libriq.id — Sistem Manajemen Perpustakaan Digital
                </p>
            </div>
        </div>
    </div>
</x-layouts.guest>
