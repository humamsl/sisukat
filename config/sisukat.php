<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Batas Ukuran File (KB)
    |--------------------------------------------------------------------------
    |
    | Semua nilai dalam kilobyte, dipakai oleh Form Request validasi upload.
    | Dapat diubah lewat .env tanpa mengubah kode.
    |
    */
    'uploads' => [
        'book_cover_max_kb' => (int) env('BOOK_COVER_MAX_KB', 2048),
        'book_file_max_kb' => (int) env('BOOK_FILE_MAX_KB', 20480),
        'tutorial_file_max_kb' => (int) env('TUTORIAL_FILE_MAX_KB', 20480),
        'tutorial_thumbnail_max_kb' => (int) env('TUTORIAL_THUMBNAIL_MAX_KB', 2048),
        'instrument_file_max_kb' => (int) env('INSTRUMENT_FILE_MAX_KB', 10240),
        'document_max_kb' => (int) env('UPLOAD_DOCUMENT_MAX_KB', 10240),
    ],

];
