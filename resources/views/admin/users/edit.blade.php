<x-layouts.admin title="Edit Pengguna — Libriq.id">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold font-heading text-stone-900">Edit Pengguna</h1>
            <p class="text-stone-500 mt-1">Perbarui data {{ $user->name }}</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-white border border-stone-200 text-stone-700 rounded-lg text-sm font-medium hover:bg-stone-50 transition-colors shadow-sm">
            &larr; Kembali ke Daftar Pengguna
        </a>
    </div>

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

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="bg-white rounded-2xl border border-stone-200 shadow-sm p-6 sm:p-8 space-y-6 max-w-2xl">
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

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Role / Peran <span class="text-red-500">*</span></label>
                <select name="role" required class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 bg-white">
                    <option value="member" {{ old('role', $user->role) === 'member' ? 'selected' : '' }}>Member / Anggota</option>
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Member ID</label>
                <input type="text" name="member_id" value="{{ old('member_id', $user->member_id) }}" placeholder="mis. M-2026-001" class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-mono">
            </div>
        </div>

        <div class="border-t border-stone-200 pt-4 space-y-4">
            <p class="text-xs text-stone-500">Isi field password di bawah hanya jika Anda ingin mengubah password pengguna ini.</p>
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

        <div class="flex justify-between items-center border-t border-stone-200 pt-6">
            <button type="button" onclick="if(confirm('Apakah Anda yakin ingin menghapus pengguna ini?')) document.getElementById('delete-user-form').submit();" class="px-4 py-2.5 bg-red-50 text-red-600 rounded-xl text-sm font-medium hover:bg-red-100 transition-colors">
                Hapus Pengguna
            </button>

            <div class="flex gap-3">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 bg-white border border-stone-200 text-stone-700 rounded-xl text-sm font-medium hover:bg-stone-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-orange-600 text-white rounded-xl text-sm font-semibold hover:bg-orange-700 transition-colors shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>

    <form id="delete-user-form" method="POST" action="{{ route('admin.users.destroy', $user) }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</x-layouts.admin>
