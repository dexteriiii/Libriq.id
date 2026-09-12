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

    <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" 
          x-data="{ 
              previewUrl: '{{ $user->avatar_url }}',
              removeAvatar: false,
              previewImage(event) {
                  const file = event.target.files[0];
                  if (file) {
                      this.previewUrl = URL.createObjectURL(file);
                      this.removeAvatar = false;
                  }
              },
              resetAvatar() {
                  this.removeAvatar = true;
                  this.previewUrl = 'https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=ea580c&background=ffedd5';
                  this.$refs.fileInput.value = '';
              }
          }" 
          class="bg-white rounded-2xl border border-stone-200 shadow-sm p-6 sm:p-8 space-y-6 max-w-2xl">
        @csrf
        @method('PUT')

        {{-- Hidden remove avatar input --}}
        <input type="hidden" name="remove_avatar" :value="removeAvatar ? '1' : '0'">

        {{-- Avatar Preview & Upload --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6 border-b border-stone-200 pb-6">
            <div class="relative shrink-0">
                <img :src="previewUrl" alt="Avatar {{ $user->name }}" class="w-24 h-24 rounded-full object-cover border-4 border-orange-100 shadow-md">
                <span class="absolute bottom-0 right-0 w-6 h-6 bg-emerald-500 border-2 border-white rounded-full" title="Akun Aktif"></span>
            </div>
            
            <div class="flex-1 space-y-2">
                <label class="block text-sm font-semibold text-stone-800">Foto Profil Administrator</label>
                <p class="text-xs text-stone-500">Format yang didukung: JPG, JPEG, PNG, atau WEBP. Ukuran file maksimal 2MB.</p>
                <div class="flex flex-wrap items-center gap-3 pt-1">
                    <label class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-orange-50 text-orange-700 text-xs font-semibold rounded-xl hover:bg-orange-100 transition-colors border border-orange-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Pilih Foto Baru
                        <input type="file" name="avatar" x-ref="fileInput" @change="previewImage($event)" accept="image/png,image/jpeg,image/jpg,image/webp" class="hidden">
                    </label>

                    @if($user->avatar_path)
                        <button type="button" @click="resetAvatar()" class="inline-flex items-center gap-1.5 px-3 py-2 bg-stone-100 text-stone-600 text-xs font-medium rounded-xl hover:bg-red-50 hover:text-red-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Hapus Foto
                        </button>
                    @endif
                </div>
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
