<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInstrumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('instrument'));
    }

    public function rules(): array
    {
        $fileMax = config('sisukat.uploads.instrument_file_max_kb');

        return [
            'category_id' => ['nullable', Rule::exists('categories', 'id')->where('type', 'instrument')],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:'.(date('Y') + 1)],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx', 'max:'.$fileMax],
            'status' => ['required', Rule::in(['draft', 'published'])],
        ];
    }
}
