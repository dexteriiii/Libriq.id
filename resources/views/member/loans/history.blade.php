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
        {{-- Desktop Table View (Hidden on mobile) --}}
        <div class="hidden md:block bg-white rounded-xl border border-stone-200 shadow-sm overflow-hidden">
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
                                <td class="px-5 py-4 tabular-nums">
                                    @php
                                        $currentFine = $loan->calculateCurrentFine();
                                    @endphp
                                    @if($currentFine > 0)
                                        <span class="text-red-600 font-bold">Rp {{ number_format($currentFine, 0, ',', '.') }}</span>
                                        @if($loan->fine_paid_at)
                                            <div class="text-emerald-600 text-[11px] font-medium">Lunas ({{ $loan->fine_paid_at->format('d M') }})</div>
                                        @elseif($loan->status === 'returned')
                                            <div class="text-red-500 text-[11px] font-medium">Belum Dibayar</div>
                                        @else
                                            <div class="text-amber-600 text-[11px] font-medium">Berjalan ({{ $loan->daysOverdue() }} hr)</div>
                                        @endif
                                    @else
                                        <span class="text-stone-400">Rp 0</span>
                                    @endif
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
                                        @elseif (in_array($loan->status, ['borrowed', 'overdue']))
                                            @if ($loan->return_status === 'pending')
                                                <span class="text-xs text-amber-700 font-semibold px-2.5 py-1 bg-amber-50 rounded-full border border-amber-200">
                                                    Pengembalian Menunggu Konfirmasi
                                                </span>
                                            @else
                                                <form method="POST" action="{{ route('member.loans.return', $loan) }}"
                                                      onsubmit="return confirm('Ajukan pengembalian buku ini? Silakan serahkan buku fisik ke petugas perpustakaan.');">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-semibold hover:bg-emerald-700 transition-colors shadow-sm">
                                                        Kembalikan
                                                    </button>
                                                </form>
                                            @endif

                                            @if ($currentFine > 0 && ! $loan->fine_paid_at)
                                                @if ($loan->fine_payment_status === 'pending')
                                                    <span class="text-xs text-blue-700 font-semibold px-2.5 py-1 bg-blue-50 rounded-full border border-blue-200">
                                                        Verifikasi Denda Diajukan
                                                    </span>
                                                @else
                                                    <form method="POST" action="{{ route('member.loans.pay-fine', $loan) }}"
                                                          onsubmit="return confirm('Konfirmasi bahwa kamu sudah membayar denda cash sebesar Rp {{ number_format($currentFine, 0, ',', '.') }} ke admin?');">
                                                        @csrf
                                                        <button type="submit" class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-xs font-semibold hover:bg-blue-700 transition-colors shadow-sm">
                                                            Bayar Cash
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif

                                            @if (! $loan->isOverdue() && $loan->status === 'borrowed' && $loan->return_status !== 'pending')
                                                @if ($loan->renewal_status === 'pending')
                                                    <span class="text-xs text-blue-600 font-semibold px-2.5 py-1 bg-blue-50 rounded-full border border-blue-200">
                                                        Perpanjangan Diajukan
                                                    </span>
                                                @elseif ($loan->hasPendingReservation())
                                                    <span class="text-xs text-stone-400 bg-stone-100 px-2.5 py-1 rounded-full" title="Ada antrean reservasi dari anggota lain">
                                                        Ada Antrean
                                                    </span>
                                                @else
                                                    <form method="POST" action="{{ route('member.loans.renew', $loan) }}"
                                                          onsubmit="return confirm('Ajukan perpanjangan masa peminjaman untuk buku ini?');">
                                                        @csrf
                                                        <button type="submit" class="px-3 py-1.5 bg-orange-50 text-orange-600 border border-orange-200 rounded-lg text-xs font-semibold hover:bg-orange-600 hover:text-white transition-colors shadow-sm">
                                                            Perpanjang
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                        @elseif ($loan->status === 'returned' && $loan->fine_amount > 0 && ! $loan->fine_paid_at)
                                            @if ($loan->fine_payment_status === 'pending')
                                                <span class="text-xs text-blue-700 font-semibold px-2.5 py-1 bg-blue-50 rounded-full border border-blue-200">
                                                    Verifikasi Denda Diajukan
                                                </span>
                                            @else
                                                <form method="POST" action="{{ route('member.loans.pay-fine', $loan) }}"
                                                      onsubmit="return confirm('Konfirmasi bahwa kamu sudah membayar denda cash sebesar Rp {{ number_format($loan->fine_amount, 0, ',', '.') }} ke admin?');">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-xs font-semibold hover:bg-blue-700 transition-colors shadow-sm">
                                                        Konfirmasi Bayar Cash
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Mobile Cards View (Visible only on mobile devices) --}}
        <div class="md:hidden space-y-3.5">
            @foreach ($loans as $loan)
                @php 
                    $badge = $loan->statusBadge(); 
                    $currentFine = $loan->calculateCurrentFine();
                @endphp
                <div class="bg-white rounded-2xl border border-stone-200 shadow-sm p-4">
                    <div class="flex items-start gap-3 pb-3 border-b border-stone-100">
                        <img src="{{ $loan->book->cover }}" class="w-12 h-16 object-cover rounded-lg shadow-sm bg-stone-100 shrink-0" alt="">
                        <div class="min-w-0 flex-1">
                            <h3 class="font-bold text-stone-900 text-sm leading-snug line-clamp-2">{{ $loan->book->title }}</h3>
                            <p class="text-xs text-stone-500 mt-0.5 truncate">{{ $loan->book->author }}</p>
                            <div class="mt-2">
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-{{ $badge['color'] }}-50 text-{{ $badge['color'] }}-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-{{ $badge['color'] }}-500"></span>
                                    {{ $badge['label'] }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 py-3 text-xs border-b border-stone-100">
                        <div>
                            <span class="text-stone-400 block">Tgl Pinjam</span>
                            <span class="font-medium text-stone-800">{{ $loan->borrow_date ? $loan->borrow_date->format('d M Y') : '—' }}</span>
                        </div>
                        <div>
                            <span class="text-stone-400 block">Jatuh Tempo</span>
                            <span class="font-medium text-stone-800">{{ $loan->due_date ? $loan->due_date->format('d M Y') : '—' }}</span>
                        </div>
                        @if($currentFine > 0)
                            <div class="col-span-2 pt-1 flex items-center justify-between bg-red-50/80 p-2 rounded-lg text-red-800">
                                <span class="font-semibold">Denda:</span>
                                <span class="font-bold text-red-600">Rp {{ number_format($currentFine, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Mobile Action Buttons --}}
                    <div class="pt-3 flex flex-col gap-2">
                        @if ($loan->status === 'pending')
                            <form method="POST" action="{{ route('member.loans.cancel', $loan) }}"
                                  onsubmit="return confirm('Batalkan permintaan peminjaman ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full py-2 bg-stone-100 hover:bg-stone-200 text-stone-700 font-semibold text-xs rounded-xl transition-colors">
                                    Batalkan Permintaan
                                </button>
                            </form>
                        @elseif (in_array($loan->status, ['borrowed', 'overdue']))
                            @if ($loan->return_status === 'pending')
                                <div class="w-full text-center py-2 bg-amber-50 text-amber-800 text-xs font-semibold rounded-xl border border-amber-200">
                                    Pengembalian Menunggu Konfirmasi
                                </div>
                            @else
                                <form method="POST" action="{{ route('member.loans.return', $loan) }}"
                                      onsubmit="return confirm('Ajukan pengembalian buku ini? Silakan serahkan buku fisik ke petugas perpustakaan.');">
                                    @csrf
                                    <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl transition-colors shadow-sm">
                                        Kembalikan Buku Fisik
                                    </button>
                                </form>
                            @endif

                            @if ($currentFine > 0 && ! $loan->fine_paid_at)
                                @if ($loan->fine_payment_status === 'pending')
                                    <div class="w-full text-center py-2 bg-blue-50 text-blue-700 text-xs font-semibold rounded-xl border border-blue-200">
                                        Verifikasi Denda Diajukan
                                    </div>
                                @else
                                    <form method="POST" action="{{ route('member.loans.pay-fine', $loan) }}"
                                          onsubmit="return confirm('Konfirmasi bahwa kamu sudah membayar denda cash sebesar Rp {{ number_format($currentFine, 0, ',', '.') }} ke admin?');">
                                        @csrf
                                        <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl transition-colors shadow-sm">
                                            Konfirmasi Bayar Denda Cash
                                        </button>
                                    </form>
                                @endif
                            @endif

                            @if (! $loan->isOverdue() && $loan->status === 'borrowed' && $loan->return_status !== 'pending')
                                @if ($loan->renewal_status === 'pending')
                                    <div class="w-full text-center py-1.5 bg-blue-50 text-blue-600 text-xs font-semibold rounded-xl">
                                        Perpanjangan Diajukan
                                    </div>
                                @elseif (! $loan->hasPendingReservation())
                                    <form method="POST" action="{{ route('member.loans.renew', $loan) }}"
                                          onsubmit="return confirm('Ajukan perpanjangan masa peminjaman untuk buku ini?');">
                                        @csrf
                                        <button type="submit" class="w-full py-2 bg-orange-50 hover:bg-orange-100 text-orange-700 font-semibold text-xs rounded-xl transition-colors">
                                            Perpanjang Masa Pinjam
                                        </button>
                                    </form>
                                @endif
                            @endif
                        @elseif ($loan->status === 'returned' && $loan->fine_amount > 0 && ! $loan->fine_paid_at)
                            @if ($loan->fine_payment_status === 'pending')
                                <div class="w-full text-center py-2 bg-blue-50 text-blue-700 text-xs font-semibold rounded-xl border border-blue-200">
                                    Verifikasi Denda Diajukan
                                </div>
                            @else
                                <form method="POST" action="{{ route('member.loans.pay-fine', $loan) }}"
                                      onsubmit="return confirm('Konfirmasi bahwa kamu sudah membayar denda cash sebesar Rp {{ number_format($loan->fine_amount, 0, ',', '.') }} ke admin?');">
                                    @csrf
                                    <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl transition-colors shadow-sm">
                                        Konfirmasi Bayar Cash
                                    </button>
                                </form>
                            @endif
                        @else
                            <div class="text-xs text-stone-400 text-center py-1">Peminjaman Selesai</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $loans->links() }}
        </div>
    @endif
</x-layouts.member>