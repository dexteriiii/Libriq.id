<x-layouts.admin title="Manajemen Sirkulasi — Libriq.id">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-heading text-stone-900">Manajemen Sirkulasi</h1>
            <p class="text-stone-500 mt-1">Pantau peminjaman, pengembalian, status overdue, dan pembayaran denda.</p>
        </div>
        <a href="{{ route('admin.loans.pending') }}" class="px-4 py-2 bg-amber-500 text-white rounded-lg text-sm font-medium hover:bg-amber-600 transition-colors shadow-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Antrean Pending Request
        </a>
    </div>

    {{-- Flash Messages --}}
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

    {{-- Search & Filter --}}
    <div class="mb-6 bg-white p-4 rounded-2xl border border-stone-200 shadow-sm">
        <form method="GET" action="{{ route('admin.loans.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari ID Peminjaman, Nama Anggota, Email, atau Judul Buku..." class="w-full pl-10 pr-4 py-2 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-stone-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <select name="status" class="px-3 py-2 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:outline-none focus:bg-white text-stone-700">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending Request</option>
                <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>Dipinjam (Active)</option>
                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Terlambat (Overdue)</option>
                <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Dikembalikan</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-stone-900 text-white text-sm font-medium rounded-xl hover:bg-stone-800 transition-colors">
                Filter
            </button>

            @if(request()->hasAny(['q', 'status']))
                <a href="{{ route('admin.loans.index') }}" class="px-4 py-2 bg-stone-100 text-stone-600 text-sm font-medium rounded-xl hover:bg-stone-200 transition-colors flex items-center justify-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Loans Table --}}
    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-stone-50 border-b border-stone-200 text-stone-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold">ID & Peminjam</th>
                        <th class="px-6 py-4 font-semibold">Buku</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold">Tanggal Pinjam / Due</th>
                        <th class="px-6 py-4 font-semibold">Denda</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200 text-sm">
                    @forelse ($loans as $loan)
                        @php $badge = $loan->statusBadge(); @endphp
                        <tr class="hover:bg-stone-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-stone-900">#{{ $loan->id }}</div>
                                <div class="text-stone-700 font-medium">{{ $loan->borrower?->name }}</div>
                                <div class="text-stone-500 text-xs">{{ $loan->borrower?->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-stone-900 font-medium line-clamp-1">{{ $loan->book?->title }}</div>
                                <div class="text-stone-500 text-xs font-mono">ISBN: {{ $loan->book?->isbn }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($badge['color'] === 'amber')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                        {{ $badge['label'] }}
                                    </span>
                                @elseif($badge['color'] === 'emerald')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                        {{ $badge['label'] }}
                                    </span>
                                @elseif($badge['color'] === 'red')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        {{ $badge['label'] }}
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-stone-100 text-stone-700">
                                        {{ $badge['label'] }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-stone-600">
                                <div>Pinjam: {{ $loan->borrow_date ? $loan->borrow_date->format('d M Y') : '-' }}</div>
                                <div>Jatuh Tempo: <strong class="{{ $loan->isOverdue() ? 'text-red-600' : '' }}">{{ $loan->due_date ? $loan->due_date->format('d M Y') : '-' }}</strong></div>
                            </td>
                            <td class="px-6 py-4 text-xs font-mono">
                                @if($loan->fine_amount > 0)
                                    <span class="font-bold text-red-600">Rp {{ number_format($loan->fine_amount, 0, ',', '.') }}</span>
                                    @if($loan->fine_paid_at)
                                        <div class="text-emerald-600 font-sans text-[10px]">Lunas ({{ $loan->fine_paid_at->format('d M') }})</div>
                                    @else
                                        <div class="text-red-500 font-sans text-[10px]">Belum Dibayar</div>
                                    @endif
                                @else
                                    <span class="text-stone-400">Rp 0</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.loans.show', $loan) }}" class="px-2.5 py-1 bg-stone-100 text-stone-700 rounded-lg text-xs font-medium hover:bg-stone-200 transition-colors">
                                        Detail
                                    </a>

                                    @if(in_array($loan->status, ['borrowed', 'overdue']))
                                        <form method="POST" action="{{ route('admin.loans.return', $loan) }}" onsubmit="return confirm('Proses pengembalian buku ini?')">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 bg-emerald-600 text-white rounded-lg text-xs font-medium hover:bg-emerald-700 transition-colors">
                                                Kembalikan
                                            </button>
                                        </form>
                                    @endif

                                    @if($loan->status === 'returned' && $loan->fine_amount > 0 && ! $loan->fine_paid_at)
                                        <form method="POST" action="{{ route('admin.loans.pay-fine', $loan) }}" onsubmit="return confirm('Catat denda ini sebagai LUNAS?')">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 bg-blue-600 text-white rounded-lg text-xs font-medium hover:bg-blue-700 transition-colors">
                                                Bayar Denda
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-stone-500">
                                Tidak ada data sirkulasi yang sesuai.
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
