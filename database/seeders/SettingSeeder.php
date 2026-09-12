<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'site_name' => 'SISUKAT',
            'site_tagline' => 'Sistem Informasi Supervisi Akademik Terpadu',
            'site_description' => 'Platform digital yang menyediakan informasi, panduan, Buku Panduan, tutorial, instrumen, dan pengelolaan dokumen untuk mendukung pelaksanaan supervisi akademik secara efektif dan terstruktur.',
            'logo' => null,
            'favicon' => null,
            'hero_background' => null,
            'running_text' => 'Selamat datang di SISUKAT — Sistem Informasi Supervisi Akademik Terpadu. Pusat informasi, Buku Panduan, tutorial, dan instrumen supervisi akademik.',
            'contact_email' => 'info@sisukat.local',
            'contact_phone' => '021-0000000',
            'contact_address' => 'Jl. Pendidikan No. 1, Indonesia',
            'social_facebook' => null,
            'social_instagram' => null,
            'social_youtube' => null,
            'copyright_text' => 'SISUKAT. All Rights Reserved.',
            'color_primary' => '#2563EB',
            'color_secondary' => '#0F172A',
            'color_accent' => '#14B8A6',
            'upload_max_size_kb' => '10240',
        ];

        foreach ($settings as $key => $value) {
            Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
