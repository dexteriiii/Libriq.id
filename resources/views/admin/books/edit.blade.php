<x-layouts.admin title="Edit Buku — Libriq.id">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold font-heading text-stone-900">Edit Data Buku</h1>
            <p class="text-stone-500 mt-1">Perbarui informasi buku "{{ $book->title }}"</p>
        </div>
        <a href="{{ route('admin.books.index') }}" class="px-4 py-2 bg-white border border-stone-200 text-stone-700 rounded-lg text-sm font-medium hover:bg-stone-50 transition-colors shadow-sm">
            &larr; Kembali ke Katalog
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

    <form method="POST" action="{{ route('admin.books.update', $book) }}" enctype="multipart/form-data" class="bg-white rounded-2xl border border-stone-200 shadow-sm p-6 sm:p-8 space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">ISBN <span class="text-red-500">*</span></label>
                <input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}" required class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-mono">
            </div>

            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Judul Buku <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $book->title) }}" required class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Penulis</label>
                <input type="text" name="author" value="{{ old('author', $book->author) }}" class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Penerbit</label>
                <input type="text" name="publisher" value="{{ old('publisher', $book->publisher) }}" class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Tahun Terbit</label>
                <input type="number" name="publish_year" value="{{ old('publish_year', $book->publish_year) }}" min="1000" :max="new Date().getFullYear()" class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Kategori</label>
                <input type="text" name="category" value="{{ old('category', $book->category) }}" class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Lokasi Rak Fisik</label>
                <input type="text" name="rack_location" value="{{ old('rack_location', $book->rack_location) }}" class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-mono">
            </div>

            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Total Stok Fisik <span class="text-red-500">*</span></label>
                <input type="number" name="total_stock" value="{{ old('total_stock', $book->total_stock) }}" min="1" required class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                <p class="text-xs text-stone-500 mt-1">Stok tersedia saat ini: <strong>{{ $book->available_stock }}</strong></p>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-stone-700 mb-1">Sinopsis</label>
            <textarea name="synopsis" rows="4" class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">{{ old('synopsis', $book->synopsis) }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-stone-200 pt-4">
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">URL Cover API</label>
                <input type="text" name="cover_url" value="{{ old('cover_url', $book->cover_url) }}" class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Upload Override Cover Baru (Maks 2MB)</label>
                <input type="file" name="cover" accept="image/png,image/jpeg,image/jpg" class="w-full text-sm text-stone-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
            </div>
        </div>

        <div class="flex items-center gap-4 bg-stone-50 p-4 rounded-xl border border-stone-200">
            <img src="{{ $book->cover }}" alt="Current Cover" class="w-16 h-20 object-cover rounded border shadow-sm">
            <div>
                <p class="text-sm font-medium text-stone-900">Cover Saat Ini</p>
                <p class="text-xs text-stone-500">{{ $book->cover_path ? 'Menggunakan custom upload' : ($book->cover_url ? 'Menggunakan URL Google Books API' : 'Menggunakan placeholder') }}</p>
            </div>
        </div>

        <div class="flex justify-between items-center border-t border-stone-200 pt-6">
            <button type="button" onclick="if(confirm('Apakah Anda yakin ingin menghapus buku ini?')) document.getElementById('delete-form').submit();" class="px-4 py-2.5 bg-red-50 text-red-600 rounded-xl text-sm font-medium hover:bg-red-100 transition-colors">
                Hapus Buku
            </button>

            <div class="flex gap-3">
                <a href="{{ route('admin.books.index') }}" class="px-5 py-2.5 bg-white border border-stone-200 text-stone-700 rounded-xl text-sm font-medium hover:bg-stone-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-orange-600 text-white rounded-xl text-sm font-semibold hover:bg-orange-700 transition-colors shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>

    <form id="delete-form" method="POST" action="{{ route('admin.books.destroy', $book) }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</x-layouts.admin>
