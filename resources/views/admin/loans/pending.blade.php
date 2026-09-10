<x-layouts.admin title="Antrean Pending Request — Libriq.id">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold font-heading text-stone-900">Antrean Permintaan Peminjaman</h1>
            <p class="text-stone-500 mt-1">Tinjau dan setujui penyerahan buku fisik kepada peminjam.</p>
        </div>
        <a href="{{ route('admin.loans.index') }}" class="px-4 py-2 bg-white border border-stone-200 text-stone-700 rounded-lg text-sm font-medium hover:bg-stone-50 transition-colors shadow-sm">
            &larr; Lihat Semua Sirkulasi
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-stone-50 border-b border-stone-200 text-stone-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold">Pemohon</th>
                        <th class="px-6 py-4 font-semibold">Buku</th>
                        <th class="px-6 py-4 font-semibold">Stok Tersedia</th>
                        <th class="px-6 py-4 font-semibold">Waktu Pengajuan</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi Fulfillment</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200 text-sm">
                    @forelse ($loans as $loan)
                        <tr class="hover:bg-stone-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-stone-900">{{ $loan->borrower?->name }}</div>
                                <div class="text-stone-500 text-xs">{{ $loan->borrower?->email }}</div>
                                <div class="text-stone-400 text-xs mt-0.5">Member ID: {{ $loan->borrower?->member_id ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-stone-900 font-medium line-clamp-1">{{ $loan->book?->title }}</div>
                                <div class="text-stone-500 text-xs font-mono">ISBN: {{ $loan->book?->isbn }}</div>
                                <div class="text-stone-500 text-xs font-mono">Rak: {{ $loan->book?->rack_location ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($loan->book?->available_stock > 2)
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                        {{ $loan->book?->available_stock }} eksemplar
                                    </span>
                                @elseif($loan->book?->available_stock > 0)
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                        {{ $loan->book?->available_stock }} eksemplar (Terbatas)
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        Stok Habis
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-stone-500">
                                {{ $loan->created_at->translatedFormat('d M Y, H:i') }}
                                <div class="text-stone-400">({{ $loan->created_at->diffForHumans() }})</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <form method="POST" action="{{ route('admin.loans.approve', $loan) }}">
                                        @csrf
                                        <button type="submit" @if($loan->book?->available_stock <= 0) disabled @endif class="px-4 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-semibold hover:bg-emerald-700 transition-colors shadow-sm disabled:opacity-50">
                                            Setujui & Serahkan
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.loans.reject', $loan) }}">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-xs font-semibold hover:bg-red-200 transition-colors">
                                            Tolak
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-stone-500">
                                Saat ini tidak ada permintaan peminjaman yang berstatus pending.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($loans->hasPages())
            <div class="px-6 py-4 border-t border-stone-200 bg-stone-50">
                {{ $loans->links() }}
            </div>
        @endif
    </div>
</x-layouts.admin>
