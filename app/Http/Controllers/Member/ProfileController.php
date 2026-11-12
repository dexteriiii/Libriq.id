<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        return view('member.profile.edit', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password'      => ['nullable', 'confirmed', Password::defaults()],
            'avatar'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_avatar' => ['nullable', 'boolean'],
        ]);

        $disk = config('filesystems.default', 'public');

        if ($request->boolean('remove_avatar')) {
            if ($user->avatar_path) {
                try {
                    Storage::disk($disk)->delete($user->avatar_path);
                } catch (\Throwable $e) {}
            }
            $validated['avatar_path'] = null;
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar_path) {
                try {
                    Storage::disk($disk)->delete($user->avatar_path);
                } catch (\Throwable $e) {}
            }
            $validated['avatar_path'] = $request->file('avatar')->store('avatars', $disk);
        }

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        unset($validated['avatar'], $validated['remove_avatar']);
        $user->update($validated);

        return back()->with('success', 'Profil Anda berhasil diperbarui.');
    }
}
