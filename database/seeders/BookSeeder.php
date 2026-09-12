<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::query()->ofType('book')->pluck('id', 'name');

        $books = [
            [
                'title' => 'Buku Panduan Supervisi Akademik',
                'category' => 'Panduan Supervisi',
                'author' => 'Tim Penyusun SISUKAT',
                'description' => 'Panduan ringkas langkah-langkah pelaksanaan supervisi akademik bagi kepala sekolah dan pengawas.',
                'pages_count' => 48,
                'year' => 2025,
            ],
            [
                'title' => 'Panduan Manajemen Sekolah Efektif',
                'category' => 'Manajemen Sekolah',
                'author' => 'Tim Penyusun SISUKAT',
                'description' => 'Kumpulan praktik baik pengelolaan sekolah untuk mendukung mutu pembelajaran.',
                'pages_count' => 62,
                'year' => 2025,
            ],
            [
                'title' => 'Pengembangan Profesi Guru Berkelanjutan',
                'category' => 'Pengembangan Profesi Guru',
                'author' => 'Tim Penyusun SISUKAT',
                'description' => 'Referensi strategi pengembangan kompetensi guru melalui hasil supervisi akademik.',
                'pages_count' => 55,
                'year' => 2026,
            ],
        ];

        foreach ($books as $data) {
            Book::query()->updateOrCreate(
                ['slug' => Str::slug($data['title'])],
                [
                    'category_id' => $categories[$data['category']] ?? null,
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'author' => $data['author'],
                    'year' => $data['year'],
                    'pages_count' => $data['pages_count'],
                    'status' => 'published',
                ]
            );
        }
    }
}
