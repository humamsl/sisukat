<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    protected $fillable = [
        'name',
        'email',
        'identity_number',
        'position',
        'school',
        'document_type',
        'description',
        'file',
        'original_filename',
        'file_size',
        'mime_type',
        'status',
        'ip_address',
        'uploaded_at',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
            'uploaded_at' => 'datetime',
        ];
    }
}
