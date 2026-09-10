<x-layouts.admin title="Detail Transaksi #{{ $loan->id }} — Libriq.id">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold font-heading text-stone-900">Detail Transaksi #{{ $loan->id }}</h1>
            <p class="text-stone-500 mt-1">Audit trail dan status transaksi peminjaman.</p>
        </div>
        <a href="{{ route('admin.loans.index') }}" class="px-4 py-2 bg-white border border-stone-200 text-stone-700 rounded-lg text-sm font-medium hover:bg-stone-50 transition-colors shadow-sm">
            &larr; Kembali ke Sirkulasi
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 bg-white rounded-2xl border border-stone-200 shadow-sm p-6 space-y-6">
            <div class="flex items-center justify-between border-b border-stone-200 pb-4">
                <div>
                    <p class="text-xs text-stone-500 font-mono">TRANSACTION ID</p>
                    <p class="text-lg font-bold font-mono text-stone-900">#{{ $loan->id }}</p>
                </div>
                <div>
                    @php $badge = $loan->statusBadge(); @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-{{ $badge['color'] }}-100 text-{{ $badge['color'] }}-800">
                        {{ $badge['label'] }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-stone-500 text-xs">Tanggal Pengajuan</p>
                    <p class="font-medium text-stone-900">{{ $loan->created_at->format('d M Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-stone-500 text-xs">Tanggal Disetujui (Borrow Date)</p>
                    <p class="font-medium text-stone-900">{{ $loan->borrow_date ? $loan->borrow_date->format('d M Y') : '-' }}</p>
                </div>
                <div>
                    <p class="text-stone-500 text-xs">Jatuh Tempo (Due Date)</p>
                    <p class="font-medium {{ $loan->isOverdue() ? 'text-red-600 font-bold' : 'text-stone-900' }}">
                        {{ $loan->due_date ? $loan->due_date->format('d M Y') : '-' }}
                    </p>
                </div>
                <div>
                    <p class="text-stone-500 text-xs">Tanggal Dikembalikan</p>
                    <p class="font-medium text-stone-900">{{ $loan->return_date ? $loan->return_date->format('d M Y') : '-' }}</p>
                </div>
            </div>

            <div class="border-t border-stone-200 pt-4">
                <h4 class="text-sm font-bold text-stone-900 mb-2">Informasi Buku</h4>
                <div class="flex gap-4 items-center bg-stone-50 p-4 rounded-xl border border-stone-200">
                    <img src="{{ $loan->book?->cover }}" alt="Book cover" class="w-12 h-16 object-cover rounded shadow-sm">
                    <div>
                        <p class="font-bold text-stone-900">{{ $loan->book?->title }}</p>
                        <p class="text-xs text-stone-500">{{ $loan->book?->author }}</p>
                        <p class="text-xs text-stone-500 font-mono mt-1">ISBN: {{ $loan->book?->isbn }} | Rak: {{ $loan->book?->rack_location ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-stone-200 pt-4">
                <h4 class="text-sm font-bold text-stone-900 mb-2">Informasi Peminjam</h4>
                <div class="bg-stone-50 p-4 rounded-xl border border-stone-200 text-sm">
                    <p class="font-bold text-stone-900">{{ $loan->borrower?->name }}</p>
                    <p class="text-stone-500 text-xs">{{ $loan->borrower?->email }}</p>
                    <p class="text-stone-500 text-xs font-mono mt-1">Role: {{ ucfirst($loan->borrower?->role) }} | Member ID: {{ $loan->borrower?->member_id ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- Action Side Card --}}
        <div class="bg-white rounded-2xl border border-stone-200 shadow-sm p-6 space-y-6">
            <h3 class="font-bold text-stone-900 text-base border-b border-stone-200 pb-3">Ringkasan Denda & Aksi</h3>

            <div class="bg-stone-50 p-4 rounded-xl border border-stone-200">
                <p class="text-xs text-stone-500">Nominal Denda</p>
                <p class="text-2xl font-bold font-mono text-stone-900 mt-1">Rp {{ number_format($loan->fine_amount, 0, ',', '.') }}</p>

                @if($loan->fine_amount > 0)
                    @if($loan->fine_paid_at)
                        <span class="inline-block mt-2 px-2.5 py-1 bg-emerald-100 text-emerald-800 text-xs font-semibold rounded-full">
                            Lunas pada {{ $loan->fine_paid_at->format('d M Y, H:i') }}
                        </span>
                    @else
                        <span class="inline-block mt-2 px-2.5 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">
                            Belum Dibayar
                        </span>
                    @endif
                @endif
            </div>

            <div class="space-y-3">
                @if($loan->status === 'pending')
                    <form method="POST" action="{{ route('admin.loans.approve', $loan) }}">
                        @csrf
                        <button type="submit" class="w-full py-2.5 bg-emerald-600 text-white font-semibold rounded-xl text-sm hover:bg-emerald-700 transition-colors">
                            Setujui Peminjaman
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.loans.reject', $loan) }}">
                        @csrf
                        <button type="submit" class="w-full py-2.5 bg-red-100 text-red-700 font-semibold rounded-xl text-sm hover:bg-red-200 transition-colors">
                            Tolak Peminjaman
                        </button>
                    </form>
                @endif

                @if(in_array($loan->status, ['borrowed', 'overdue']))
                    <form method="POST" action="{{ route('admin.loans.return', $loan) }}">
                        @csrf
                        <button type="submit" class="w-full py-2.5 bg-emerald-600 text-white font-semibold rounded-xl text-sm hover:bg-emerald-700 transition-colors">
                            Proses Pengembalian Buku
                        </button>
                    </form>
                @endif

                @if($loan->status === 'returned' && $loan->fine_amount > 0 && ! $loan->fine_paid_at)
                    <form method="POST" action="{{ route('admin.loans.pay-fine', $loan) }}">
                        @csrf
                        <button type="submit" class="w-full py-2.5 bg-blue-600 text-white font-semibold rounded-xl text-sm hover:bg-blue-700 transition-colors">
                            Tandai Denda Lunas
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-layouts.admin>
