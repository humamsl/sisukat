<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tutorial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TutorialSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::query()->ofType('tutorial')->pluck('id', 'name');

        $tutorials = [
            [
                'title' => 'Cara Menggunakan SISUKAT untuk Pemula',
                'category' => 'Tutorial Penggunaan SISUKAT',
                'type' => 'article',
                'description' => 'Langkah awal menjelajahi menu dan fitur utama SISUKAT.',
                'content' => 'Panduan langkah demi langkah menavigasi SISUKAT mulai dari Beranda hingga Upload Dokumen.',
            ],
            [
                'title' => 'Tahapan Pelaksanaan Supervisi Akademik',
                'category' => 'Tutorial Supervisi Akademik',
                'type' => 'article',
                'description' => 'Ringkasan tahapan supervisi akademik dari perencanaan hingga tindak lanjut.',
                'content' => 'Penjelasan tahapan perencanaan, pelaksanaan, dan evaluasi supervisi akademik di sekolah.',
            ],
            [
                'title' => 'Video Panduan Mengisi Instrumen Supervisi',
                'category' => 'Tutorial Pengisian Instrumen',
                'type' => 'video',
                'description' => 'Video panduan visual mengisi instrumen observasi pembelajaran.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            ],
        ];

        foreach ($tutorials as $data) {
            Tutorial::query()->updateOrCreate(
                ['slug' => Str::slug($data['title'])],
                [
                    'category_id' => $categories[$data['category']] ?? null,
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'content' => $data['content'] ?? null,
                    'video_url' => $data['video_url'] ?? null,
                    'type' => $data['type'],
                    'status' => 'published',
                ]
            );
        }
    }
}
