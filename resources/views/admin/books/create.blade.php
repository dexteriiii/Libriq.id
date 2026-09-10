<x-layouts.admin title="Tambah Buku Baru — Libriq.id">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold font-heading text-stone-900">Tambah Buku Baru</h1>
            <p class="text-stone-500 mt-1">Cari via Google Books API untuk auto-fill metadata, atau isi form secara manual.</p>
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

    <div x-data="{
        loading: false,
        apiSearchQuery: '',
        searchType: 'isbn',
        errorMessage: '',
        warningMessage: '',
        existingBook: null,
        formData: {
            isbn: '{{ old('isbn') }}',
            title: '{{ old('title') }}',
            author: '{{ old('author') }}',
            publisher: '{{ old('publisher') }}',
            publish_year: '{{ old('publish_year') }}',
            synopsis: '{{ old('synopsis') }}',
            category: '{{ old('category') }}',
            rack_location: '{{ old('rack_location') }}',
            total_stock: '{{ old('total_stock', 1) }}',
            cover_url: '{{ old('cover_url') }}'
        },
        async fetchFromApi() {
            if (!this.apiSearchQuery.trim()) {
                this.errorMessage = 'Silakan masukkan ISBN atau judul buku untuk dicari.';
                return;
            }
            this.loading = true;
            this.errorMessage = '';
            this.warningMessage = '';
            this.existingBook = null;

            try {
                const response = await fetch('{{ route('admin.books.fetch-api') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        [this.searchType]: this.apiSearchQuery
                    })
                });

                const resData = await response.json();

                if (response.status === 409) {
                    this.warningMessage = resData.warning;
                    this.existingBook = resData.existing;
                    return;
                }

                if (!response.ok) {
                    this.errorMessage = resData.error || 'Gagal mengambil data dari Google Books API.';
                    return;
                }

                if (resData.data) {
                    const item = resData.data;
                    if (item.isbn) this.formData.isbn = item.isbn;
                    if (item.title) this.formData.title = item.title;
                    if (item.author) this.formData.author = item.author;
                    if (item.publisher) this.formData.publisher = item.publisher;
                    if (item.publish_year) this.formData.publish_year = item.publish_year;
                    if (item.synopsis) this.formData.synopsis = item.synopsis;
                    if (item.category) this.formData.category = item.category;
                    if (item.cover_url) this.formData.cover_url = item.cover_url;
                }
            } catch (err) {
                this.errorMessage = 'Terjadi kesalahan koneksi saat memanggil Google Books API.';
            } finally {
                this.loading = false;
            }
        }
    }">
        {{-- Google Books Search Card --}}
        <div class="bg-gradient-to-r from-orange-500 to-amber-600 rounded-2xl p-6 text-white shadow-md mb-8">
            <h3 class="text-lg font-bold font-heading mb-2">Pencarian Otomatis Google Books API</h3>
            <p class="text-orange-100 text-sm mb-4">Masukkan ISBN atau Judul Buku untuk auto-fill metadata secara otomatis.</p>

            <div class="flex flex-col sm:flex-row gap-3">
                <select x-model="searchType" class="bg-white/10 text-white border border-white/20 rounded-xl px-3 py-2 text-sm focus:outline-none focus:bg-white/20">
                    <option value="isbn" class="text-stone-900">ISBN</option>
                    <option value="title" class="text-stone-900">Judul Buku</option>
                </select>

                <div class="flex-1 relative">
                    <input type="text" x-model="apiSearchQuery" @keydown.enter.prevent="fetchFromApi()" :placeholder="searchType === 'isbn' ? 'Contoh: 9780134494166' : 'Contoh: Clean Architecture'" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-white placeholder-orange-200 text-sm focus:outline-none focus:bg-white/20">
                </div>

                <button type="button" @click="fetchFromApi()" :disabled="loading" class="px-5 py-2 bg-white text-orange-600 font-semibold rounded-xl text-sm hover:bg-orange-50 transition-colors shadow-sm disabled:opacity-50 flex items-center justify-center gap-2">
                    <span x-show="!loading">Cari Data</span>
                    <span x-show="loading" x-cloak>Sedang Memuat...</span>
                </button>
            </div>

            {{-- API Messages --}}
            <template x-if="errorMessage">
                <div class="mt-4 p-3 bg-red-500/20 border border-red-200/30 rounded-xl text-sm text-white">
                    <span x-text="errorMessage"></span>
                </div>
            </template>

            <template x-if="warningMessage">
                <div class="mt-4 p-4 bg-amber-500/30 border border-amber-200/40 rounded-xl text-sm text-white flex justify-between items-center">
                    <div>
                        <span x-text="warningMessage"></span>
                    </div>
                    <template x-if="existingBook">
                        <form method="POST" :action="`{{ url('admin/books') }}/${existingBook.id}/stock`" class="flex gap-2 items-center">
                            @csrf
                            <input type="number" name="quantity" value="1" min="1" class="w-16 px-2 py-1 bg-white text-stone-900 rounded text-xs">
                            <button type="submit" class="px-3 py-1 bg-white text-amber-700 font-semibold rounded text-xs">Tambah Stok</button>
                        </form>
                    </template>
                </div>
            </template>
        </div>

        {{-- Final Staging Form --}}
        <form method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data" class="bg-white rounded-2xl border border-stone-200 shadow-sm p-6 sm:p-8 space-y-6">
            @csrf

            <h3 class="text-lg font-bold font-heading text-stone-900 border-b border-stone-200 pb-3">Form Details Buku (Staging Form)</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1">ISBN <span class="text-red-500">*</span></label>
                    <input type="text" name="isbn" x-model="formData.isbn" required class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-mono">
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1">Judul Buku <span class="text-red-500">*</span></label>
                    <input type="text" name="title" x-model="formData.title" required class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1">Penulis</label>
                    <input type="text" name="author" x-model="formData.author" class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1">Penerbit</label>
                    <input type="text" name="publisher" x-model="formData.publisher" class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1">Tahun Terbit</label>
                    <input type="number" name="publish_year" x-model="formData.publish_year" min="1000" :max="new Date().getFullYear()" class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1">Kategori</label>
                    <input type="text" name="category" x-model="formData.category" placeholder="mis. Teknologi, Filsafat, Fiksi" class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1">Lokasi Rak Fisik</label>
                    <input type="text" name="rack_location" x-model="formData.rack_location" placeholder="mis. T-01-A" class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-mono">
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1">Jumlah Eksemplar (Stok Awal) <span class="text-red-500">*</span></label>
                    <input type="number" name="total_stock" x-model="formData.total_stock" min="1" required class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Sinopsis / Ringkasan</label>
                <textarea name="synopsis" x-model="formData.synopsis" rows="4" class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-stone-200 pt-4">
                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1">URL Cover (dari Google Books API)</label>
                    <input type="text" name="cover_url" x-model="formData.cover_url" placeholder="https://..." class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1">Upload Custom Cover (Override File, Maks 2MB)</label>
                    <input type="file" name="cover" accept="image/png,image/jpeg,image/jpg" class="w-full text-sm text-stone-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                </div>
            </div>

            {{-- Preview Image if available --}}
            <template x-if="formData.cover_url">
                <div class="flex items-center gap-4 bg-stone-50 p-4 rounded-xl border border-stone-200">
                    <img :src="formData.cover_url" alt="Cover Preview" class="w-16 h-20 object-cover rounded border shadow-sm">
                    <div>
                        <p class="text-sm font-medium text-stone-900">Preview Cover dari API</p>
                        <p class="text-xs text-stone-500">Akan digunakan jika tidak ada custom cover yang diupload.</p>
                    </div>
                </div>
            </template>

            <div class="flex justify-end gap-3 border-t border-stone-200 pt-6">
                <a href="{{ route('admin.books.index') }}" class="px-5 py-2.5 bg-white border border-stone-200 text-stone-700 rounded-xl text-sm font-medium hover:bg-stone-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-orange-600 text-white rounded-xl text-sm font-semibold hover:bg-orange-700 transition-colors shadow-sm">
                    Simpan Buku Ke Database
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>
