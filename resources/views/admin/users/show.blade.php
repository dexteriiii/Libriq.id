<x-layouts.admin title="Profil {{ $user->name }} — Libriq.id">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold font-heading text-stone-900">Profil Pengguna</h1>
            <p class="text-stone-500 mt-1">Detail akun dan riwayat sirkulasi {{ $user->name }}.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="px-4 py-2 bg-orange-600 text-white rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors shadow-sm">
                Edit Profil
            </a>
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-white border border-stone-200 text-stone-700 rounded-lg text-sm font-medium hover:bg-stone-50 transition-colors shadow-sm">
                &larr; Kembali
            </a>
        </div>
    </div>

    {{-- User Summary Card --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm flex items-center gap-4">
            <img src="{{ $user->avatar_url }}" alt="Avatar {{ $user->name }}" class="w-14 h-14 rounded-full object-cover border-2 border-orange-200 shrink-0">
            <div>
                <h3 class="font-bold text-stone-900">{{ $user->name }}</h3>
                <p class="text-xs text-stone-500">{{ $user->email }}</p>
                <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-semibold {{ $user->isAdmin() ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                    {{ ucfirst($user->role) }}
                </span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm">
            <p class="text-xs text-stone-500">Pinjaman Aktif</p>
            <p class="text-2xl font-bold text-stone-900 mt-1">{{ $activeLoanCount }}</p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm">
            <p class="text-xs text-stone-500">Denda Belum Dibayar</p>
            <p class="text-2xl font-bold font-mono text-red-600 mt-1">Rp {{ number_format($unpaidFine, 0, ',', '.') }}</p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm">
            <p class="text-xs text-stone-500">Total Denda Akumulasi</p>
            <p class="text-2xl font-bold font-mono text-stone-900 mt-1">Rp {{ number_format($totalFine, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- User Loan History --}}
    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-stone-200 bg-stone-50/50">
            <h3 class="font-bold text-stone-900">Riwayat Peminjaman Buku</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-stone-50 text-stone-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-3 font-medium">Buku</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 font-medium">Tanggal Pinjam</th>
                        <th class="px-6 py-3 font-medium">Jatuh Tempo</th>
                        <th class="px-6 py-3 font-medium">Denda</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200 text-sm">
                    @forelse ($loans as $loan)
                        @php $badge = $loan->statusBadge(); @endphp
                        <tr class="hover:bg-stone-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-stone-900">
                                {{ $loan->book?->title }}
                                <div class="text-xs text-stone-500 font-mono">ISBN: {{ $loan->book?->isbn }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-{{ $badge['color'] }}-100 text-{{ $badge['color'] }}-800">
                                    {{ $badge['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-stone-500">
                                {{ $loan->borrow_date ? $loan->borrow_date->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-xs text-stone-500">
                                {{ $loan->due_date ? $loan->due_date->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-xs font-mono">
                                Rp {{ number_format($loan->fine_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-stone-500">
                                Belum ada riwayat peminjaman.
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
