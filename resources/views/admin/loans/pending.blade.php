<x-layouts.admin title="Antrean Konfirmasi & Permintaan — Libriq.id">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-heading text-stone-900">Antrean Konfirmasi Sirkulasi</h1>
            <p class="text-stone-500 mt-1">Konfirmasi penyerahan buku, pengembalian buku, perpanjangan, dan pembayaran denda.</p>
        </div>
        <a href="{{ route('admin.loans.index') }}" class="px-4 py-2 bg-white border border-stone-200 text-stone-700 rounded-lg text-sm font-medium hover:bg-stone-50 transition-colors shadow-sm self-start">
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

    {{-- 1. PENGEMBALIAN BUKU PENDING --}}
    @if(isset($returns) && $returns->isNotEmpty())
        <div class="mb-8">
            <div class="flex items-center gap-2 mb-3">
                <span class="p-1.5 bg-emerald-100 text-emerald-700 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </span>
                <h2 class="text-lg font-bold font-heading text-stone-900">Pengajuan Pengembalian Buku ({{ $returns->count() }})</h2>
            </div>
            <div class="bg-white rounded-2xl border border-emerald-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-emerald-50/70 border-b border-emerald-100 text-emerald-900 text-xs uppercase tracking-wider">
                                <th class="px-6 py-4 font-semibold">Peminjam</th>
                                <th class="px-6 py-4 font-semibold">Buku</th>
                                <th class="px-6 py-4 font-semibold">Jatuh Tempo & Denda</th>
                                <th class="px-6 py-4 font-semibold">Diajukan Pada</th>
                                <th class="px-6 py-4 font-semibold text-right">Aksi Konfirmasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-200 text-sm">
                            @foreach ($returns as $ret)
                                @php 
                                    $currentFine = $ret->calculateCurrentFine();
                                    $isOverdue = $ret->isOverdue();
                                @endphp
                                <tr class="hover:bg-emerald-50/20 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-stone-900">{{ $ret->borrower?->name }}</div>
                                        <div class="text-stone-500 text-xs">{{ $ret->borrower?->email }}</div>
                                        <div class="text-stone-400 text-xs font-mono">ID: {{ $ret->borrower?->member_id ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-stone-900 font-medium line-clamp-1">{{ $ret->book?->title }}</div>
                                        <div class="text-stone-500 text-xs font-mono">ISBN: {{ $ret->book?->isbn }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs">
                                        <div>Jatuh tempo: <span class="{{ $isOverdue ? 'text-red-600 font-bold' : 'text-stone-700 font-medium' }}">{{ $ret->due_date ? $ret->due_date->format('d M Y') : '-' }}</span></div>
                                        @if($currentFine > 0)
                                            <div class="text-red-600 font-bold mt-0.5">Denda: Rp {{ number_format($currentFine, 0, ',', '.') }} (Terlambat {{ $ret->daysOverdue() }} hari)</div>
                                        @else
                                            <div class="text-emerald-600 font-medium mt-0.5">Tepat Waktu (Denda Rp 0)</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-xs text-stone-500">
                                        {{ $ret->return_requested_at ? $ret->return_requested_at->translatedFormat('d M Y, H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <form method="POST" action="{{ route('admin.loans.return', $ret) }}" onsubmit="return confirm('Konfirmasi bahwa buku fisik telah diterima di perpustakaan?');">
                                                @csrf
                                                <button type="submit" class="px-4 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-semibold hover:bg-emerald-700 transition-colors shadow-sm">
                                                    Terima & Konfirmasi Kembali
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('admin.loans.return-reject', $ret) }}" onsubmit="return confirm('Tolak pengajuan pengembalian buku ini?');">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-xs font-semibold hover:bg-red-200 transition-colors">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- 2. KONFIRMASI PEMBAYARAN DENDA PENDING --}}
    @if(isset($finePayments) && $finePayments->isNotEmpty())
        <div class="mb-8">
            <div class="flex items-center gap-2 mb-3">
                <span class="p-1.5 bg-amber-100 text-amber-700 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
                <h2 class="text-lg font-bold font-heading text-stone-900">Konfirmasi Pembayaran Denda ({{ $finePayments->count() }})</h2>
            </div>
            <div class="bg-white rounded-2xl border border-amber-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-amber-50/70 border-b border-amber-100 text-amber-900 text-xs uppercase tracking-wider">
                                <th class="px-6 py-4 font-semibold">Peminjam</th>
                                <th class="px-6 py-4 font-semibold">Buku & Status</th>
                                <th class="px-6 py-4 font-semibold">Nominal Denda</th>
                                <th class="px-6 py-4 font-semibold">Diajukan Pada</th>
                                <th class="px-6 py-4 font-semibold text-right">Aksi Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-200 text-sm">
                            @foreach ($finePayments as $fp)
                                @php $fineVal = $fp->calculateCurrentFine(); @endphp
                                <tr class="hover:bg-amber-50/20 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-stone-900">{{ $fp->borrower?->name }}</div>
                                        <div class="text-stone-500 text-xs">{{ $fp->borrower?->email }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-stone-900 font-medium line-clamp-1">{{ $fp->book?->title }}</div>
                                        <div class="text-xs text-stone-500">Status Buku: <span class="font-semibold">{{ $fp->status === 'returned' ? 'Sudah Dikembalikan' : 'Sedang Dipinjam' }}</span></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-mono font-bold text-red-600 text-base">Rp {{ number_format($fineVal, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-stone-500">
                                        {{ $fp->fine_payment_requested_at ? $fp->fine_payment_requested_at->translatedFormat('d M Y, H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <form method="POST" action="{{ route('admin.loans.pay-fine', $fp) }}" onsubmit="return confirm('Konfirmasi bahwa uang cash denda sebesar Rp {{ number_format($fineVal, 0, ',', '.') }} telah diterima lunas?');">
                                                @csrf
                                                <button type="submit" class="px-4 py-1.5 bg-blue-600 text-white rounded-lg text-xs font-semibold hover:bg-blue-700 transition-colors shadow-sm">
                                                    Konfirmasi Denda Lunas
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('admin.loans.pay-fine-reject', $fp) }}" onsubmit="return confirm('Tolak verifikasi pembayaran denda ini? (User belum bayar / uang belum diterima)');">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-xs font-semibold hover:bg-red-200 transition-colors">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- 3. PERPANJANGAN PENDING --}}
    @if(isset($renewals) && $renewals->isNotEmpty())
        <div class="mb-8">
            <div class="flex items-center gap-2 mb-3">
                <span class="p-1.5 bg-blue-100 text-blue-600 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </span>
                <h2 class="text-lg font-bold font-heading text-stone-900">Pengajuan Perpanjangan Peminjaman ({{ $renewals->count() }})</h2>
            </div>
            <div class="bg-white rounded-2xl border border-blue-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-blue-50/60 border-b border-blue-100 text-blue-900 text-xs uppercase tracking-wider">
                                <th class="px-6 py-4 font-semibold">Peminjam</th>
                                <th class="px-6 py-4 font-semibold">Buku</th>
                                <th class="px-6 py-4 font-semibold">Jatuh Tempo Saat Ini</th>
                                <th class="px-6 py-4 font-semibold">Diajukan Pada</th>
                                <th class="px-6 py-4 font-semibold text-right">Aksi Perpanjangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-200 text-sm">
                            @foreach ($renewals as $renewal)
                                <tr class="hover:bg-blue-50/20 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-stone-900">{{ $renewal->borrower?->name }}</div>
                                        <div class="text-stone-500 text-xs">{{ $renewal->borrower?->email }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-stone-900 font-medium line-clamp-1">{{ $renewal->book?->title }}</div>
                                        <div class="text-stone-500 text-xs font-mono">ISBN: {{ $renewal->book?->isbn }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-medium text-stone-700">
                                        {{ $renewal->due_date ? $renewal->due_date->format('d M Y') : '-' }}
                                        <div class="text-[11px] text-stone-500">Perpanjangan ke-{{ $renewal->renewal_count + 1 }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-stone-500">
                                        {{ $renewal->renewal_requested_at ? $renewal->renewal_requested_at->translatedFormat('d M Y, H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <form method="POST" action="{{ route('admin.loans.renew-approve', $renewal) }}">
                                                @csrf
                                                <button type="submit" class="px-4 py-1.5 bg-blue-600 text-white rounded-lg text-xs font-semibold hover:bg-blue-700 transition-colors shadow-sm">
                                                    Setujui (+7 Hari)
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('admin.loans.renew-reject', $renewal) }}">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-xs font-semibold hover:bg-red-200 transition-colors">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- QUICK VERIFICATION BOX --}}
    <div class="mb-8 bg-gradient-to-r from-amber-500/10 via-orange-500/10 to-emerald-500/10 border border-amber-200/80 rounded-2xl p-6 shadow-sm">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="max-w-xl">
                <div class="flex items-center gap-2 text-amber-700 font-semibold text-xs tracking-wider uppercase mb-1">
                    <span class="p-1 bg-amber-500 text-white rounded-md">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </span>
                    Verifikasi Peminjaman Buku
                </div>
                <h2 class="text-xl font-bold font-heading text-stone-900">Cocokkan 5 Kode Angka dari Anggota</h2>
                <p class="text-stone-600 text-sm mt-1">
                    Minta anggota menunjukkan 5 digit kode verifikasi bukti pinjam mereka, lalu masukkan kode di samping untuk verifikasi dan serahkan buku secara instan.
                </p>
            </div>

            <form method="POST" action="{{ route('admin.loans.verify-code') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                @csrf
                <div>
                    <input type="text" name="code" placeholder="Cth: 62517" maxlength="5" pattern="[0-9]{5}" required
                           class="w-full sm:w-44 text-center font-mono text-xl tracking-widest font-black py-2.5 px-4 bg-white border-2 border-amber-300 rounded-xl focus:outline-none focus:border-amber-600 focus:ring-4 focus:ring-amber-500/20 text-stone-900 placeholder:text-stone-300 shadow-xs" autocomplete="off">
                </div>
                <button type="submit" class="px-5 py-3 bg-amber-600 hover:bg-amber-700 text-white font-semibold text-sm rounded-xl transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Cocokkan & Serahkan
                </button>
            </form>
        </div>
    </div>

    {{-- 4. PERMINTAAN PEMINJAMAN BARU --}}
    <div class="mb-3 flex items-center justify-between">
        <h2 class="text-lg font-bold font-heading text-stone-900">Permintaan Peminjaman Baru ({{ $loans->total() }})</h2>
    </div>

    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-stone-50 border-b border-stone-200 text-stone-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold">Pemohon</th>
                        <th class="px-6 py-4 font-semibold">Buku</th>
                        <th class="px-6 py-4 font-semibold">Kode Bukti</th>
                        <th class="px-6 py-4 font-semibold">Stok Tersedia</th>
                        <th class="px-6 py-4 font-semibold">Waktu Pengajuan</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi Verifikasi & Penyerahan</th>
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
                                @if($loan->verification_code)
                                    <span class="inline-flex items-center font-mono font-black text-sm px-2.5 py-1 bg-amber-100 text-amber-900 rounded-lg border border-amber-300 tracking-widest shadow-xs">
                                        {{ $loan->verification_code }}
                                    </span>
                                @else
                                    <span class="text-stone-400 text-xs">-</span>
                                @endif
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
                                    <form method="POST" action="{{ route('admin.loans.approve', $loan) }}" class="flex items-center gap-1.5">
                                        @csrf
                                        @if($loan->verification_code)
                                            <input type="text" name="verification_code" placeholder="Ketik kode" maxlength="5" pattern="[0-9]{5}" required
                                                   class="w-24 px-2.5 py-1.5 text-xs font-mono text-center border border-stone-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white shadow-xs" title="Masukkan 5 digit kode verifikasi anggota">
                                        @endif
                                        <button type="submit" @if($loan->book?->available_stock <= 0) disabled @endif class="px-3.5 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-semibold hover:bg-emerald-700 transition-colors shadow-sm disabled:opacity-50 whitespace-nowrap">
                                            Cocokkan & Serahkan
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.loans.reject', $loan) }}" onsubmit="return confirm('Tolak permohonan peminjaman ini?');">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1.5 bg-red-100 text-red-700 rounded-lg text-xs font-semibold hover:bg-red-200 transition-colors">
                                            Tolak
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-stone-500">
                                Saat ini tidak ada permintaan peminjaman baru yang berstatus pending.
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
