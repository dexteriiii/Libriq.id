<x-layouts.admin title="Tambah Pengguna — Libriq.id">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold font-heading text-stone-900">Tambah Pengguna Baru</h1>
            <p class="text-stone-500 mt-1">Daftarkan akun administrator atau anggota perpustakaan baru.</p>
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

    <form method="POST" action="{{ route('admin.users.store') }}" class="bg-white rounded-2xl border border-stone-200 shadow-sm p-6 sm:p-8 space-y-6 max-w-2xl">
        @csrf

        <div>
            <label class="block text-sm font-medium text-stone-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-stone-700 mb-1">Alamat Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Role / Peran <span class="text-red-500">*</span></label>
                <select name="role" required class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 bg-white">
                    <option value="member" {{ old('role') === 'member' ? 'selected' : '' }}>Member / Anggota</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Member ID (Opsional)</label>
                <input type="text" name="member_id" value="{{ old('member_id') }}" placeholder="mis. M-2026-001" class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-mono">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-stone-200 pt-4">
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Password <span class="text-red-500">*</span></label>
                <input type="password" name="password" required class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Konfirmasi Password <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation" required class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
            </div>
        </div>

        <div class="flex justify-end gap-3 border-t border-stone-200 pt-6">
            <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 bg-white border border-stone-200 text-stone-700 rounded-xl text-sm font-medium hover:bg-stone-50 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-orange-600 text-white rounded-xl text-sm font-semibold hover:bg-orange-700 transition-colors shadow-sm">
                Simpan Pengguna
            </button>
        </div>
    </form>
</x-layouts.admin>
