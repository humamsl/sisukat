<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        Page::query()->updateOrCreate(
            ['slug' => 'pendahuluan'],
            [
                'title' => 'Pendahuluan',
                'status' => 'published',
                'content' => <<<'HTML'
                <h2>Latar Belakang</h2>
                <p>Peningkatan kualitas pembelajaran di sekolah tidak dapat dilepaskan dari peran pengawas dan kepala sekolah dalam melaksanakan supervisi akademik secara berkala dan terstruktur. Supervisi akademik menjadi instrumen kunci untuk memastikan proses belajar mengajar berjalan sesuai standar, sekaligus menjadi sarana pembinaan profesional bagi guru.</p>

                <h2>Pengertian Supervisi Akademik</h2>
                <p>Supervisi akademik adalah serangkaian kegiatan pembinaan yang dilakukan untuk membantu guru mengembangkan kemampuannya dalam mengelola proses pembelajaran, sehingga tercapai tujuan pendidikan yang telah ditetapkan.</p>

                <h2>Tujuan Supervisi Akademik</h2>
                <ul>
                    <li>Membantu guru merencanakan pembelajaran yang efektif.</li>
                    <li>Meningkatkan kompetensi profesional guru secara berkelanjutan.</li>
                    <li>Memastikan proses pembelajaran sesuai standar kurikulum.</li>
                    <li>Menjadi dasar pengambilan keputusan pembinaan dan pengembangan sekolah.</li>
                </ul>

                <h2>Manfaat Supervisi Akademik</h2>
                <ul>
                    <li>Kualitas pembelajaran di kelas meningkat secara terukur.</li>
                    <li>Guru memperoleh umpan balik yang konstruktif dan berkelanjutan.</li>
                    <li>Sekolah memiliki data akurat untuk perencanaan program pengembangan.</li>
                </ul>

                <h2>Prinsip Supervisi Akademik</h2>
                <ul>
                    <li>Objektif</li>
                    <li>Sistematis</li>
                    <li>Demokratis</li>
                    <li>Kooperatif</li>
                    <li>Konstruktif</li>
                    <li>Berkesinambungan</li>
                </ul>

                <h2>Ruang Lingkup</h2>
                <p>Ruang lingkup supervisi akademik meliputi perencanaan pembelajaran, pelaksanaan proses belajar mengajar di kelas, penilaian hasil belajar, serta tindak lanjut pembinaan guru berdasarkan hasil observasi dan evaluasi.</p>
                HTML,
            ]
        );

        Page::query()->updateOrCreate(
            ['slug' => 'petunjuk-penggunaan'],
            [
                'title' => 'Petunjuk Penggunaan',
                'status' => 'published',
                'content' => <<<'HTML'
                <h2>Langkah Penggunaan SISUKAT</h2>
                <ol>
                    <li>Buka halaman SISUKAT melalui browser.</li>
                    <li>Baca informasi pada halaman Pendahuluan untuk memahami dasar supervisi akademik.</li>
                    <li>Pelajari Buku Saku Digital yang tersedia sebagai panduan praktis.</li>
                    <li>Ikuti Tutorial yang sesuai dengan kebutuhan Anda.</li>
                    <li>Unduh Instrumen Supervisi yang relevan dengan kegiatan yang akan dilakukan.</li>
                    <li>Isi instrumen sesuai dengan hasil observasi/kegiatan supervisi.</li>
                    <li>Upload dokumen hasil pengisian melalui halaman Upload Dokumen.</li>
                </ol>
                HTML,
            ]
        );

        Page::query()->updateOrCreate(
            ['slug' => 'tentang-sisukat'],
            [
                'title' => 'Tentang SISUKAT',
                'status' => 'published',
                'content' => <<<'HTML'
                <h3>Apa itu SISUKAT?</h3>
                <p>SISUKAT (Sistem Informasi Supervisi Akademik Terpadu) adalah platform digital yang memusatkan seluruh informasi, panduan, dan dokumen terkait supervisi akademik dalam satu tempat.</p>

                <h3>Mengapa SISUKAT dibuat?</h3>
                <p>SISUKAT dibuat untuk menjawab kebutuhan transformasi digital dalam pelaksanaan supervisi akademik, sehingga proses lebih terpadu, transparan, dan mudah diakses oleh semua pihak terkait.</p>

                <h3>Siapa pengguna SISUKAT?</h3>
                <p>SISUKAT ditujukan untuk guru, kepala sekolah, pengawas sekolah, serta pihak lain yang berkepentingan dalam pelaksanaan supervisi akademik.</p>

                <h3>Apa manfaat SISUKAT?</h3>
                <p>SISUKAT memudahkan akses informasi supervisi, mempercepat proses pengelolaan dokumen, dan menjadikan pelaksanaan supervisi akademik lebih efektif dan terstruktur.</p>
                HTML,
            ]
        );
    }
}
