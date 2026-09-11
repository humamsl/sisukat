<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Instrument;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InstrumentSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::query()->ofType('instrument')->pluck('id', 'name');

        $instruments = [
            [
                'title' => 'Instrumen Supervisi Kinerja Guru',
                'category' => 'Instrumen Supervisi Guru',
            ],
            [
                'title' => 'Instrumen Observasi Proses Pembelajaran',
                'category' => 'Instrumen Observasi Pembelajaran',
            ],
            [
                'title' => 'Instrumen Perencanaan Pembelajaran (RPP)',
                'category' => 'Instrumen Perencanaan Pembelajaran',
            ],
            [
                'title' => 'Instrumen Evaluasi Hasil Supervisi',
                'category' => 'Instrumen Evaluasi',
            ],
            [
                'title' => 'Instrumen Tindak Lanjut Hasil Supervisi',
                'category' => 'Instrumen Tindak Lanjut',
            ],
        ];

        foreach ($instruments as $data) {
            Instrument::query()->updateOrCreate(
                ['slug' => Str::slug($data['title'])],
                [
                    'category_id' => $categories[$data['category']] ?? null,
                    'title' => $data['title'],
                    'description' => "Dokumen {$data['title']} untuk mendukung pelaksanaan supervisi akademik.",
                    'file' => 'instruments/seed-placeholder.pdf',
                    'file_type' => 'pdf',
                    'file_size' => 128_000,
                    'year' => 2026,
                    'status' => 'published',
                ]
            );
        }
    }
}
