<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;

class UploadDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $maxKb = config('sisukat.uploads.document_max_kb');

        return [
            'file' => ['required', 'file', 'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png', 'max:'.$maxKb],
        ];
    }

    public function messages(): array
    {
        return [
            'file.max' => 'Ukuran file melebihi batas maksimal yang diizinkan.',
            'file.mimes' => 'Jenis file tidak diizinkan. Gunakan PDF, DOC, DOCX, XLS, XLSX, JPG, atau PNG.',
        ];
    }
}
