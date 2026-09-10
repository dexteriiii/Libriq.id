<x-layouts.admin title="Pengaturan Sistem — Libriq.id">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold font-heading text-stone-900">Pengaturan Sistem</h1>
            <p class="text-stone-500 mt-1">Konfigurasi durasi peminjaman, tarif denda keterlambatan, dan batas peminjaman.</p>
        </div>
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

    <form method="POST" action="{{ route('admin.settings.update') }}" class="bg-white rounded-2xl border border-stone-200 shadow-sm p-6 sm:p-8 space-y-6 max-w-2xl">
        @csrf
        @method('PUT')

        <div class="space-y-4">
            <h3 class="text-base font-bold font-heading text-stone-900 border-b border-stone-200 pb-2">Aturan Sirkulasi & Denda</h3>

            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Durasi Peminjaman Default (Hari)</label>
                <input type="number" name="loan_duration_days" value="{{ old('loan_duration_days', $settings['loan_duration_days'] ?? 7) }}" min="1" max="365" required class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                <p class="text-xs text-stone-500 mt-1">Jumlah hari peminjaman standar sejak buku disetujui admin.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Tarif Denda Keterlambatan Per Hari (Rupiah)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-stone-400 text-sm">Rp</span>
                    <input type="number" name="fine_per_day" value="{{ old('fine_per_day', $settings['fine_per_day'] ?? 2000) }}" min="0" required class="w-full pl-12 pr-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-mono">
                </div>
                <p class="text-xs text-stone-500 mt-1">Nominal denda yang dihitung otomatis oleh system job harian per hari keterlambatan.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Kuota Maksimum Peminjaman Aktif Per Member</label>
                <input type="number" name="max_active_loans" value="{{ old('max_active_loans', $settings['max_active_loans'] ?? 3) }}" min="1" max="50" required class="w-full px-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                <p class="text-xs text-stone-500 mt-1">Batas maksimal buku yang dapat dipinjam secara bersamaan oleh seorang member.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Batas Maksimum Denda Belum Lunas (Rupiah)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-stone-400 text-sm">Rp</span>
                    <input type="number" name="max_unpaid_fine" value="{{ old('max_unpaid_fine', $settings['max_unpaid_fine'] ?? 20000) }}" min="0" required class="w-full pl-12 pr-4 py-2 border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-mono">
                </div>
                <p class="text-xs text-stone-500 mt-1">Jika denda belum lunas mencapai/melebihi nominal ini, member tidak dapat mengajukan peminjaman buku baru.</p>
            </div>
        </div>

        <div class="flex justify-end pt-4 border-t border-stone-200">
            <button type="submit" class="px-6 py-2.5 bg-orange-600 text-white rounded-xl text-sm font-semibold hover:bg-orange-700 transition-colors shadow-sm">
                Simpan Pengaturan
            </button>
        </div>
    </form>
</x-layouts.admin>
