<x-layouts.member title="Edit Profil — Libriq.id">
    <div class="mb-6">
        <h1 class="font-heading text-2xl font-bold text-stone-900">Edit Profil Saya</h1>
        <p class="text-stone-500 text-sm mt-1">Perbarui informasi akun dan kata sandimu.</p>
    </div>

    @if (session('success'))
        <div class="rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm p-4 mb-6">
            <div class="font-bold mb-1">Terjadi kesalahan validasi:</div>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl border border-stone-200 p-6 sm:p-8 max-w-2xl">
        <form method="POST" action="{{ route('member.profile.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Alamat Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Member ID</label>
                <input type="text" value="{{ $user->member_id ?? 'Belum Diatur' }}" disabled class="w-full px-4 py-2 bg-stone-100 border border-stone-200 rounded-xl text-sm text-stone-500 font-mono">
                <p class="text-xs text-stone-400 mt-1">ID Anggota diatur oleh admin perpustakaan.</p>
            </div>

            <div class="border-t border-stone-200 pt-4 space-y-4">
                <p class="text-xs text-stone-500">Kosongkan kolom password jika tidak ingin mengubah password.</p>
                
                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1">Password Baru (Opsional)</label>
                    <input type="password" name="password" class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                </div>
            </div>

            <div class="flex justify-end gap-3 border-t border-stone-200 pt-6">
                <a href="{{ route('member.dashboard') }}" class="px-5 py-2.5 bg-white border border-stone-200 text-stone-700 rounded-xl text-sm font-medium hover:bg-stone-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-orange-600 text-white rounded-xl text-sm font-semibold hover:bg-orange-700 transition-colors shadow-sm">
                    Simpan Profil
                </button>
            </div>
        </form>
    </div>
</x-layouts.member>
