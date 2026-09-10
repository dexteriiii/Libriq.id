<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        return [
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'password'  => ['nullable', 'confirmed', Password::defaults()],
            'role'      => ['required', 'in:admin,member'],
            'member_id' => ['nullable', 'string', 'max:50', Rule::unique('users', 'member_id')->ignore($userId)],
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
