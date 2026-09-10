<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $bookId = $this->route('book')?->id;

        return [
            'isbn'          => ['required', 'string', 'max:20', Rule::unique('books', 'isbn')->ignore($bookId)],
            'title'         => ['required', 'string', 'max:255'],
            'author'        => ['nullable', 'string', 'max:255'],
            'publisher'     => ['nullable', 'string', 'max:255'],
            'publish_year'  => ['nullable', 'integer', 'min:1000', 'max:' . date('Y')],
            'synopsis'      => ['nullable', 'string'],
            'category'      => ['nullable', 'string', 'max:100'],
            'rack_location' => ['nullable', 'string', 'max:50'],
            'total_stock'   => ['required', 'integer', 'min:1'],
            'cover_url'     => ['nullable', 'url', 'max:500'],
            'cover'         => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'isbn.unique'      => 'ISBN ini sudah terdaftar di katalog.',
            'total_stock.min'  => 'Jumlah stok minimal 1 eksemplar.',
            'cover.max'        => 'Ukuran file cover maksimal 2MB.',
            'cover.mimes'      => 'Format cover hanya JPG atau PNG.',
        ];
    }
}
