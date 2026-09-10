<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'  => ['required', 'confirmed', Password::defaults()],
            'role'      => ['required', 'in:admin,member'],
            'member_id' => ['nullable', 'string', 'max:50', 'unique:users,member_id'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique'     => 'Email ini sudah digunakan oleh pengguna lain.',
            'member_id.unique' => 'ID anggota ini sudah digunakan.',
            'role.in'          => 'Role hanya boleh admin atau member.',
        ];
    }
}
