<x-layouts.member title="Peminjaman Saya — Libriq.id">
    <div class="mb-6">
        <h1 class="font-heading text-2xl font-bold text-stone-900">Peminjaman Saya</h1>
        <p class="text-stone-500 text-sm mt-1">Riwayat dan status seluruh permintaan peminjamanmu.</p>
    </div>

    @if (session('success'))
        <div class="flex items-start gap-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 mb-6 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="flex items-start gap-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 mb-6 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if ($loans->isEmpty())
        {{-- Empty State --}}
        <div class="bg-white rounded-xl border border-stone-200 shadow-sm py-16 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-10 w-10 text-stone-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
            </svg>
            <p class="text-stone-500 text-sm">Kamu belum pernah mengajukan peminjaman.</p>
            <a href="{{ route('member.catalog.index') }}" class="inline-block mt-3 text-sm font-medium text-orange-600 hover:text-orange-700">Jelajahi Katalog →</a>
        </div>
    @else
        <div class="bg-white rounded-xl border border-stone-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead class="bg-stone-50 text-stone-500 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="text-left font-semibold px-5 py-3">Buku</th>
                            <th class="text-left font-semibold px-5 py-3">Tgl Pinjam</th>
                            <th class="text-left font-semibold px-5 py-3">Jatuh Tempo</th>
                            <th class="text-left font-semibold px-5 py-3">Status</th>
                            <th class="text-left font-semibold px-5 py-3">Denda</th>
                            <th class="text-right font-semibold px-5 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @foreach ($loans as $loan)
                            @php $badge = $loan->statusBadge(); @endphp
                            <tr class="hover:bg-stone-50/60 transition-colors">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $loan->book->cover }}" class="w-9 h-12 object-cover rounded-md shadow-sm bg-stone-100 flex-shrink-0" alt="">
                                        <span class="font-medium text-stone-900">{{ $loan->book->title }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-stone-500 tabular-nums">{{ $loan->borrow_date ? $loan->borrow_date->format('d M Y') : '—' }}</td>
                                <td class="px-5 py-4 text-stone-500 tabular-nums">{{ $loan->due_date ? $loan->due_date->format('d M Y') : '—' }}</td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-{{ $badge['color'] }}-50 text-{{ $badge['color'] }}-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-{{ $badge['color'] }}-500"></span>
                                        {{ $badge['label'] }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 tabular-nums {{ $loan->fine_amount > 0 ? 'text-red-600 font-semibold' : 'text-stone-400' }}">
                                    {{ $loan->fine_amount > 0 ? 'Rp' . number_format($loan->fine_amount, 0, ',', '.') : '—' }}
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if ($loan->status === 'pending')
                                            <form method="POST" action="{{ route('member.loans.cancel', $loan) }}"
                                                  onsubmit="return confirm('Batalkan permintaan peminjaman ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs font-medium text-stone-500 hover:text-red-600 transition-colors">Batalkan</button>
                                            </form>
                                        @elseif ($loan->status === 'borrowed')
                                            @if ($loan->renewal_status === 'pending')
                                                <span class="text-xs text-blue-600 font-semibold px-2.5 py-1 bg-blue-50 rounded-full border border-blue-200">
                                                    Perpanjangan Menunggu Persetujuan
                                                </span>
                                            @elseif ($loan->hasPendingReservation())
                                                <span class="text-xs text-stone-400 bg-stone-100 px-2.5 py-1 rounded-full" title="Ada antrean reservasi dari anggota lain">
                                                    Ada Antrean
                                                </span>
                                            @elseif (! $loan->isOverdue())
                                                <form method="POST" action="{{ route('member.loans.renew', $loan) }}"
                                                      onsubmit="return confirm('Ajukan perpanjangan masa peminjaman untuk buku ini?');">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1.5 bg-orange-50 text-orange-600 border border-orange-200 rounded-lg text-xs font-semibold hover:bg-orange-600 hover:text-white transition-colors shadow-sm">
                                                        Perpanjang
                                                    </button>
                                                </form>
                                            @endif

                                            <form method="POST" action="{{ route('member.loans.return', $loan) }}"
                                                  onsubmit="return confirm('Kembalikan buku ini sekarang?');">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-semibold hover:bg-emerald-700 transition-colors shadow-sm">
                                                    Kembalikan
                                                </button>
                                            </form>
                                        @elseif ($loan->status === 'overdue')
                                            <form method="POST" action="{{ route('member.loans.return', $loan) }}"
                                                  onsubmit="return confirm('Kembalikan buku ini sekarang?');">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-red-600 text-white rounded-lg text-xs font-semibold hover:bg-red-700 transition-colors shadow-sm">
                                                    Kembalikan
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $loans->links() }}
        </div>
    @endif
</x-layouts.member>