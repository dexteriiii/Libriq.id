<x-layouts.admin title="Manajemen Pengguna — Libriq.id">
    <div x-data="{ showImportModal: false }">
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold font-heading text-stone-900">Manajemen Pengguna</h1>
                <p class="text-stone-500 mt-1">Kelola data administrator dan anggota perpustakaan.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <button 
                    type="button" 
                    @click="showImportModal = true" 
                    class="w-full sm:w-auto justify-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold transition-colors shadow-sm flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    Impor Data Siswa (Excel / CSV)
                </button>

                <a href="{{ route('admin.users.create') }}" class="w-full sm:w-auto justify-center px-4 py-2.5 bg-orange-600 text-white rounded-xl text-sm font-semibold hover:bg-orange-700 transition-colors shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Pengguna Manual
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2 shadow-xs">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if (session('warning'))
            <div class="mb-6 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-sm flex items-center gap-2 shadow-xs">
                <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>{{ session('warning') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm flex items-center gap-2 shadow-xs">
                <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Filter --}}
        <div class="mb-6 bg-white p-4 rounded-2xl border border-stone-200 shadow-sm">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Nama, Email, NIS, Kelas, atau Member ID..." class="w-full pl-10 pr-4 py-2 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-stone-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>

                <select name="role" class="px-3 py-2 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:outline-none focus:bg-white text-stone-700">
                    <option value="">Semua Role</option>
                    <option value="member" {{ request('role') == 'member' ? 'selected' : '' }}>Member</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>

                <select name="active" class="px-3 py-2 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:outline-none focus:bg-white text-stone-700">
                    <option value="">Semua Status Aktivasi</option>
                    <option value="activated" {{ request('active') == 'activated' ? 'selected' : '' }}>Sudah Aktivasi (Aktif)</option>
                    <option value="pending" {{ request('active') == 'pending' ? 'selected' : '' }}>Belum Aktivasi</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-stone-900 text-white text-sm font-medium rounded-xl hover:bg-stone-800 transition-colors">
                    Filter
                </button>

                @if(request()->hasAny(['q', 'role', 'active']))
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-stone-100 text-stone-600 text-sm font-medium rounded-xl hover:bg-stone-200 transition-colors flex items-center justify-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto w-full scrollbar-thin">
                <table class="w-full text-left border-collapse min-w-[720px]">
                    <thead>
                        <tr class="bg-stone-50 border-b border-stone-200 text-stone-500 text-xs uppercase tracking-wider">
                            <th class="px-6 py-4 font-semibold">Pengguna</th>
                            <th class="px-6 py-4 font-semibold">Role, NIS & Kelas</th>
                            <th class="px-6 py-4 font-semibold">Status Akun</th>
                            <th class="px-6 py-4 font-semibold text-center">Pinjaman Aktif</th>
                            <th class="px-6 py-4 font-semibold">Terdaftar Pada</th>
                            <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-200 text-sm">
                        @forelse ($users as $u)
                            <tr class="hover:bg-stone-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $u->avatar_url }}" alt="Avatar {{ $u->name }}" class="w-9 h-9 rounded-full object-cover border border-stone-200 shrink-0">
                                        <div class="min-w-0">
                                            <div class="font-bold text-stone-900 truncate">{{ $u->name }}</div>
                                            @if($u->email)
                                                <div class="text-stone-500 text-xs truncate">{{ $u->email }}</div>
                                            @else
                                                <div class="text-amber-600 text-xs italic">Email belum diisi siswa</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $u->isAdmin() ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ ucfirst($u->role) }}
                                    </span>
                                    <div class="mt-1 space-y-0.5 text-xs">
                                        @if($u->nis)
                                            <div class="text-stone-700 font-mono font-medium">NIS: {{ $u->nis }}</div>
                                        @elseif($u->member_id)
                                            <div class="text-stone-500 font-mono">{{ $u->member_id }}</div>
                                        @else
                                            <div class="text-stone-400">-</div>
                                        @endif

                                        @if($u->kelas)
                                            <div class="text-stone-600 font-medium flex items-center gap-1">
                                                <span class="text-stone-400">Kelas:</span>
                                                <span class="bg-stone-100 text-stone-800 px-1.5 py-0.5 rounded text-[11px] font-semibold">{{ $u->kelas }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($u->role === 'member')
                                        @if($u->needsActivation())
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                Belum Aktivasi
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Aktif
                                            </span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-stone-100 text-stone-700">
                                            Admin
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-bold {{ $u->active_loans_count > 0 ? 'text-amber-600' : 'text-stone-400' }}">
                                        {{ $u->active_loans_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-stone-500 text-xs">
                                    {{ $u->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.users.show', $u) }}" class="p-1.5 text-stone-400 hover:text-stone-900 transition-colors" title="Detail Pengguna">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $u) }}" class="p-1.5 text-stone-400 hover:text-orange-600 transition-colors" title="Edit Pengguna">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </a>
                                        @if($u->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.users.destroy', $u) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $u->name }}? Tindakan ini tidak dapat dibatalkan.');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-stone-400 hover:text-red-600 transition-colors" title="Hapus Pengguna">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-stone-400">
                                    Tidak ada data pengguna yang sesuai dengan pencarian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="p-4 border-t border-stone-200">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

        {{-- Modal Impor Excel / CSV --}}
        <div 
            x-show="showImportModal" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto bg-stone-900/60 backdrop-blur-xs flex items-center justify-center p-4"
        >
            <div 
                @click.away="showImportModal = false" 
                class="bg-white rounded-2xl border border-stone-200 shadow-xl max-w-lg w-full p-6 sm:p-7 overflow-hidden relative animate-in fade-in zoom-in-95 duration-200"
            >
                <div class="flex items-center justify-between pb-4 border-b border-stone-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-heading font-bold text-stone-900 text-lg">Impor Data Siswa</h2>
                            <p class="text-xs text-stone-500">Unggah file Excel (.xlsx) atau CSV</p>
                        </div>
                    </div>
                    <button type="button" @click="showImportModal = false" class="text-stone-400 hover:text-stone-700 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Panduan Format & Download Template --}}
                <div class="mt-4 p-3.5 bg-orange-50/70 border border-orange-200 rounded-xl text-xs text-orange-950 space-y-2">
                    <div class="font-semibold text-orange-800 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-orange-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Ketentuan Format File:
                    </div>
                    <ul class="list-disc list-inside space-y-1 text-orange-900/90 pl-1">
                        <li>File wajib memuat 3 kolom: <strong>NIS</strong>, <strong>Nama Siswa</strong>, dan <strong>Kelas</strong>.</li>
                        <li>Email dan Password dikosongkan. Siswa akan mengisi sendiri saat pertama kali login (aktivasi akun).</li>
                        <li>Baris pertama digunakan sebagai nama kolom (header).</li>
                    </ul>
                    <div class="pt-1">
                        <a href="{{ route('admin.users.template') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-orange-700 hover:text-orange-900 underline">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Unduh Contoh Template Excel (.xlsx)
                        </a>
                    </div>
                </div>

                {{-- Form Upload --}}
                <form method="POST" action="{{ route('admin.users.import') }}" enctype="multipart/form-data" class="mt-5 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1.5">Pilih File Excel / CSV</label>
                        <input 
                            type="file" 
                            name="file" 
                            required 
                            accept=".xlsx,.xls,.csv"
                            class="block w-full text-xs text-stone-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-stone-100 file:text-stone-700 hover:file:bg-stone-200 border border-stone-200 rounded-xl cursor-pointer bg-stone-50 p-1 focus:outline-none"
                        >
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-stone-100">
                        <button 
                            type="button" 
                            @click="showImportModal = false" 
                            class="px-4 py-2 bg-stone-100 hover:bg-stone-200 text-stone-700 rounded-xl text-xs font-semibold transition"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold transition shadow-xs flex items-center gap-1.5"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            Mulai Impor Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>