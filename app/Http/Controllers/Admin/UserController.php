<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // ─── Index ───────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = User::withCount([
            'loans as active_loans_count' => fn($q) => $q->whereIn('status', ['pending', 'borrowed', 'overdue']),
        ]);

        if ($q = $request->input('q')) {
            $query->where(function ($sq) use ($q) {
                $sq->where('name', 'like', "%{$q}%")
                   ->orWhere('email', 'like', "%{$q}%")
                   ->orWhere('member_id', 'like', "%{$q}%");
            });
        }

        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        $users = $query->orderBy('name')->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    // ─── Show ─────────────────────────────────────────────────────────────────

    public function show(User $user)
    {
        $loans = $user->loans()
            ->with('book')
            ->latest()
            ->paginate(10);

        $totalFine    = $user->loans()->sum('fine_amount');
        $unpaidFine   = $user->unpaidFines();
        $activeLoanCount = $user->activeLoans()->count();

        return view('admin.users.show', compact('user', 'loans', 'totalFine', 'unpaidFine', 'activeLoanCount'));
    }

    // ─── Create ───────────────────────────────────────────────────────────────

    public function create()
    {
        return view('admin.users.create');
    }

    // ─── Store ────────────────────────────────────────────────────────────────

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', "Pengguna \"{$user->name}\" berhasil dibuat.");
    }

    // ─── Edit ─────────────────────────────────────────────────────────────────

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // ─── Update ───────────────────────────────────────────────────────────────

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', "Data pengguna \"{$user->name}\" berhasil diperbarui.");
    }

    // ─── Destroy ──────────────────────────────────────────────────────────────

    public function destroy(User $user): RedirectResponse
    {
        // Tidak bisa hapus user dengan peminjaman aktif
        if ($user->activeLoans()->exists()) {
            return back()->with('error', "Pengguna \"{$user->name}\" tidak dapat dihapus karena masih memiliki peminjaman aktif.");
        }

        // Tidak bisa hapus diri sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Pengguna \"{$name}\" berhasil dihapus.");
    }
}
