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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'identity_number' => ['nullable', 'string', 'max:100'],
            'position' => ['required', 'string', 'max:255'],
            'school' => ['required', 'string', 'max:255'],
            'document_type' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'file' => ['required', 'file', 'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png', 'max:'.$maxKb],
            'agreement' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'agreement.accepted' => 'Anda harus menyetujui pernyataan sebelum mengirim dokumen.',
            'file.max' => 'Ukuran file melebihi batas maksimal yang diizinkan.',
            'file.mimes' => 'Jenis file tidak diizinkan. Gunakan PDF, DOC, DOCX, XLS, XLSX, JPG, atau PNG.',
        ];
    }
}
