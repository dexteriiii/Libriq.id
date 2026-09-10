<x-layouts.member title="Peminjaman Saya — Libriq.id">
    <div class="mb-6">
        <h1 class="font-heading text-2xl font-bold text-stone-900">Peminjaman Saya</h1>
        <p class="text-stone-500 text-sm mt-1">Riwayat dan status seluruh permintaan peminjamanmu.</p>
    </div>

    @if (session('success'))
        <div class="rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 mb-6">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 mb-6">
            {{ session('error') }}
        </div>
    @endif

    @if ($loans->isEmpty())
        <div class="bg-white rounded-xl border border-stone-200 py-16 text-center">
            <p class="text-stone-500 text-sm">Kamu belum pernah mengajukan peminjaman.</p>
            <a href="{{ route('member.catalog.index') }}" class="inline-block mt-3 text-sm font-medium text-orange-600 hover:text-orange-700">Jelajahi Katalog →</a>
        </div>
    @else
        <div class="bg-white rounded-xl border border-stone-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-stone-50 text-stone-500 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="text-left font-medium px-5 py-3">Buku</th>
                        <th class="text-left font-medium px-5 py-3">Tgl Pinjam</th>
                        <th class="text-left font-medium px-5 py-3">Jatuh Tempo</th>
                        <th class="text-left font-medium px-5 py-3">Status</th>
                        <th class="text-left font-medium px-5 py-3">Denda</th>
                        <th class="text-right font-medium px-5 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @foreach ($loans as $loan)
                        @php $badge = $loan->statusBadge(); @endphp
                        <tr>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $loan->book->cover }}" class="w-8 h-11 object-cover rounded bg-stone-100 flex-shrink-0" alt="">
                                    <span class="font-medium text-stone-900">{{ $loan->book->title }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-stone-500">{{ $loan->borrow_date ? $loan->borrow_date->format('d M Y') : '—' }}</td>
                            <td class="px-5 py-4 text-stone-500">{{ $loan->due_date ? $loan->due_date->format('d M Y') : '—' }}</td>
                            <td class="px-5 py-4">
                                <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-{{ $badge['color'] }}-50 text-{{ $badge['color'] }}-700">
                                    {{ $badge['label'] }}
                                </span>
                            </td>
                            <td class="px-5 py-4 {{ $loan->fine_amount > 0 ? 'text-red-600 font-medium' : 'text-stone-400' }}">
                                {{ $loan->fine_amount > 0 ? 'Rp' . number_format($loan->fine_amount, 0, ',', '.') : '—' }}
                            </td>
                            <td class="px-5 py-4 text-right">
                                @if ($loan->status === 'pending')
                                    <form method="POST" action="{{ route('member.loans.cancel', $loan) }}"
                                          onsubmit="return confirm('Batalkan permintaan peminjaman ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-medium text-stone-500 hover:text-red-600">Batalkan</button>
                                    </form>
                                @elseif (in_array($loan->status, ['borrowed', 'overdue']))
                                    <form method="POST" action="{{ route('member.loans.return', $loan) }}"
                                          onsubmit="return confirm('Kembalikan buku ini sekarang?');">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-semibold hover:bg-emerald-700 transition-colors">
                                            Kembalikan Buku
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $loans->links() }}
        </div>
    @endif
</x-layouts.member>
