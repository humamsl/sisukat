<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'book' => [
                'Panduan Supervisi',
                'Manajemen Sekolah',
                'Pengembangan Profesi Guru',
            ],
            'tutorial' => [
                'Tutorial Penggunaan SISUKAT',
                'Tutorial Supervisi Akademik',
                'Tutorial Pengisian Instrumen',
                'Tutorial Upload Dokumen',
            ],
            'instrument' => [
                'Instrumen Supervisi Guru',
                'Instrumen Observasi Pembelajaran',
                'Instrumen Perencanaan Pembelajaran',
                'Instrumen Evaluasi',
                'Instrumen Tindak Lanjut',
            ],
        ];

        foreach ($categories as $type => $names) {
            foreach ($names as $name) {
                Category::query()->updateOrCreate(
                    ['slug' => Str::slug($name)],
                    ['name' => $name, 'type' => $type]
                );
            }
        }
    }
}
