<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Mews\Purifier\Facades\Purifier;

class UpdateTutorialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('tutorial'));
    }

    public function rules(): array
    {
        $fileMax = config('sisukat.uploads.tutorial_file_max_kb');
        $thumbMax = config('sisukat.uploads.tutorial_thumbnail_max_kb');

        return [
            'category_id' => ['nullable', Rule::exists('categories', 'id')->where('type', 'tutorial')],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', Rule::in(['video', 'article', 'pdf', 'image', 'link'])],
            'content' => ['required_if:type,article', 'nullable', 'string'],
            'video_url' => ['required_if:type,video', 'nullable', 'url', 'max:500'],
            'external_url' => ['required_if:type,link', 'nullable', 'url', 'max:500'],
            'file' => [
                'nullable',
                'file',
                Rule::when(fn () => $this->input('type') === 'pdf', ['mimes:pdf']),
                Rule::when(fn () => $this->input('type') === 'image', ['mimes:jpg,jpeg,png,webp']),
                'max:'.$fileMax,
            ],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:'.$thumbMax],
            'status' => ['required', Rule::in(['draft', 'published'])],
        ];
    }

    public function validated($key = null, $default = null): array
    {
        $data = parent::validated($key, $default);

        if (! empty($data['content'])) {
            $data['content'] = Purifier::clean($data['content']);
        }

        return $data;
    }
}
