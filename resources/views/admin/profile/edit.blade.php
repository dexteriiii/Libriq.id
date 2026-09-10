<x-layouts.admin title="Profil Admin — Libriq.id">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold font-heading text-stone-900">Profil Saya (Administrator)</h1>
            <p class="text-stone-500 mt-1">Perbarui foto profil, nama, email, dan kata sandimu.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-white border border-stone-200 text-stone-700 rounded-lg text-sm font-medium hover:bg-stone-50 transition-colors shadow-sm">
            &larr; Kembali ke Dashboard
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm">
            <div class="font-bold mb-1">Terjadi kesalahan validasi:</div>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" class="bg-white rounded-2xl border border-stone-200 shadow-sm p-6 sm:p-8 space-y-6 max-w-2xl">
        @csrf
        @method('PUT')

        {{-- Avatar Preview & Upload --}}
        <div class="flex items-center gap-6 border-b border-stone-200 pb-6">
            <img src="{{ $user->avatar_url }}" alt="Avatar {{ $user->name }}" class="w-20 h-20 rounded-full object-cover border-2 border-orange-500 shadow-sm">
            
            <div class="flex-1">
                <label class="block text-sm font-medium text-stone-700 mb-1">Upload Foto Profil Baru (JPG/PNG, Maks 2MB)</label>
                <input type="file" name="avatar" accept="image/png,image/jpeg,image/jpg" class="w-full text-sm text-stone-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-stone-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-stone-700 mb-1">Alamat Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
        </div>

        <div class="border-t border-stone-200 pt-4 space-y-4">
            <p class="text-xs text-stone-500">Kosongkan kolom password jika tidak ingin mengubah password.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1">Password Baru (Opsional)</label>
                    <input type="password" name="password" class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 border-t border-stone-200 pt-6">
            <a href="{{ route('admin.dashboard') }}" class="px-5 py-2.5 bg-white border border-stone-200 text-stone-700 rounded-xl text-sm font-medium hover:bg-stone-50 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-orange-600 text-white rounded-xl text-sm font-semibold hover:bg-orange-700 transition-colors shadow-sm">
                Simpan Profil
            </button>
        </div>
    </form>
</x-layouts.admin>
