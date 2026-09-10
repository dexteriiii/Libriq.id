<x-layouts.admin title="Manajemen Pengguna — Libriq.id">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-heading text-stone-900">Manajemen Pengguna</h1>
            <p class="text-stone-500 mt-1">Kelola data administrator dan anggota perpustakaan.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-orange-600 text-white rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors shadow-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Pengguna Baru
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

    {{-- Filter --}}
    <div class="mb-6 bg-white p-4 rounded-2xl border border-stone-200 shadow-sm">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Nama, Email, atau Member ID..." class="w-full pl-10 pr-4 py-2 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-stone-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <select name="role" class="px-3 py-2 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:outline-none focus:bg-white text-stone-700">
                <option value="">Semua Role</option>
                <option value="member" {{ request('role') == 'member' ? 'selected' : '' }}>Member</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-stone-900 text-white text-sm font-medium rounded-xl hover:bg-stone-800 transition-colors">
                Filter
            </button>

            @if(request()->hasAny(['q', 'role']))
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-stone-100 text-stone-600 text-sm font-medium rounded-xl hover:bg-stone-200 transition-colors flex items-center justify-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-stone-50 border-b border-stone-200 text-stone-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold">Pengguna</th>
                        <th class="px-6 py-4 font-semibold">Role & Member ID</th>
                        <th class="px-6 py-4 font-semibold text-center">Pinjaman Aktif</th>
                        <th class="px-6 py-4 font-semibold">Terdaftar Pada</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200 text-sm">
                    @forelse ($users as $u)
                        <tr class="hover:bg-stone-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-stone-900">{{ $u->name }}</div>
                                <div class="text-stone-500 text-xs">{{ $u->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $u->isAdmin() ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ ucfirst($u->role) }}
                                </span>
                                <div class="text-stone-500 text-xs font-mono mt-1">{{ $u->member_id ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-bold {{ $u->active_loans_count > 0 ? 'text-amber-600' : 'text-stone-400' }}">
                                    {{ $u->active_loans_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-stone-500">
                                {{ $u->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.show', $u) }}" class="px-2.5 py-1 bg-stone-100 text-stone-700 rounded-lg text-xs font-medium hover:bg-stone-200 transition-colors">
                                        Detail
                                    </a>
                                    <a href="{{ route('admin.users.edit', $u) }}" class="px-2.5 py-1 bg-orange-50 text-orange-700 rounded-lg text-xs font-medium hover:bg-orange-100 transition-colors">
                                        Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-stone-500">
                                Tidak ada data pengguna.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-stone-200 bg-stone-50">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-layouts.admin>
