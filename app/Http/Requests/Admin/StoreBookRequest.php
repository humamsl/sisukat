<?php

namespace App\Http\Requests\Admin;

use App\Models\Book;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Book::class);
    }

    public function rules(): array
    {
        $coverMax = config('sisukat.uploads.book_cover_max_kb');
        $fileMax = config('sisukat.uploads.book_file_max_kb');

        return [
            'category_id' => ['nullable', Rule::exists('categories', 'id')->where('type', 'book')],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'author' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:'.(date('Y') + 1)],
            'pages_count' => ['nullable', 'integer', 'min:1'],
            'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:'.$coverMax],
            'file' => ['required_without:read_url', 'file', 'mimes:pdf', 'max:'.$fileMax],
            'read_url' => ['required_without:file', 'nullable', 'url', 'max:2048'],
            'status' => ['required', Rule::in(['draft', 'published'])],
        ];
    }
}
