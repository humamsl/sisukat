# SISUKAT — Sistem Informasi Supervisi Akademik Terpadu

Platform digital yang menyediakan informasi, panduan, buku saku, tutorial, instrumen, dan pengelolaan dokumen untuk mendukung pelaksanaan supervisi akademik secara efektif dan terstruktur.

SISUKAT adalah portal ber-login: hanya Home, Login, dan Daftar yang bisa diakses tanpa akun. Pengunjung mendaftar sendiri (role `user`) untuk membaca konten dan mengunggah dokumen; staf (`admin`/`super_admin`/`reviewer`) mengelola konten lewat dashboard admin.

Dibangun dengan Laravel 12, Tailwind CSS v4, dan Alpine.js.

---

## Daftar Isi

1. [Requirement](#1-requirement)
2. [Installation (Development)](#2-installation-development)
3. [Struktur Database](#3-struktur-database)
4. [Migration](#4-migration)
5. [Seeder](#5-seeder)
6. [Routing](#6-routing)
7. [Authentication](#7-authentication)
8. [Authorization](#8-authorization)
9. [File Upload](#9-file-upload)
10. [Admin Dashboard](#10-admin-dashboard)
11. [Deployment (Ubuntu + Nginx)](#11-deployment-ubuntu--nginx)
12. [Backup](#12-backup)
13. [Maintenance](#13-maintenance)
14. [Troubleshooting](#14-troubleshooting)

---

## 1. Requirement

| Komponen | Versi Minimum |
|---|---|
| PHP | 8.3 (ekstensi: mbstring, pdo_mysql, openssl, tokenizer, xml, ctype, json, curl, fileinfo, gd) |
| MySQL / MariaDB | MySQL 8.0+ / MariaDB 10.6+ |
| Node.js | 20 LTS |
| Composer | 2.x |
| Web Server | Nginx (produksi) / `php artisan serve` (lokal) |

---

## 2. Installation (Development)

```bash
git clone <repository-url> sisukat
cd sisukat

composer install
npm install

cp .env.example .env
php artisan key:generate
```

Edit `.env`, sesuaikan minimal:

```env
APP_URL=http://sisukat.test
DB_DATABASE=sisukat
DB_USERNAME=root
DB_PASSWORD=
```

```bash
php artisan migrate --seed
php artisan storage:link
npm run build
php artisan serve
```

Untuk development frontend dengan hot-reload, jalankan `npm run dev` di terminal terpisah.

**Akun admin demo** (dari `AdminUserSeeder`, ganti passwordnya setelah instalasi):

```
Email    : admin@sisukat.local
Password : password
Role     : super_admin
```

---

## 3. Struktur Database

```
users            — akun staf (super_admin | admin | reviewer) + akun publik (user)
categories       — taksonomi bersama untuk books/tutorials/instruments (kolom `type`)
pages            — konten Pendahuluan, Petunjuk Penggunaan, Tentang SISUKAT
books            — Buku Saku Digital (PDF + cover)
tutorials        — Tutorial (article/video/pdf/image/link)
instruments      — Instrumen Supervisi (pdf/doc/docx/xls/xlsx)
uploads          — Dokumen masuk dari form publik "Upload Dokumen"
activity_logs    — audit log aksi admin (login/logout/create/update/delete/upload/download)
settings         — key-value pengaturan situs
```

Relasi utama: `categories` 1—N `{books, tutorials, instruments}` (dibatasi `type` di level aplikasi via `Rule::exists(...)->where('type', ...)`, bukan foreign key per tipe, karena satu tabel `categories` melayani tiga modul). `users` 1—N `activity_logs`.

ERD lengkap (teks) ada di riwayat perencanaan proyek; struktur kolom persis mengikuti file migration di `database/migrations/`.

---

## 4. Migration

```bash
php artisan migrate            # jalankan migration baru
php artisan migrate:fresh      # drop semua tabel lalu migrate ulang (HATI-HATI: hapus data)
php artisan migrate:rollback   # rollback batch migration terakhir
php artisan migrate:status     # lihat status setiap migration
```

Urutan migration mengikuti dependency: `users → categories → pages → books → tutorials → instruments → uploads → activity_logs → settings`, diikuti migration index performa di akhir.

---

## 5. Seeder

```bash
php artisan db:seed
# atau sekaligus saat migrate:
php artisan migrate:fresh --seed
```

`DatabaseSeeder` memanggil (berurutan): `AdminUserSeeder` (1 admin), `CategorySeeder` (12 kategori: 3 buku, 4 tutorial, 5 instrumen), `PageSeeder` (3 halaman konten), `BookSeeder` (3 buku), `TutorialSeeder` (3 tutorial), `InstrumentSeeder` (5 instrumen), `SettingSeeder` (16 pengaturan situs). Tidak ada data pribadi nyata — semua nama/kontak adalah data contoh.

---

## 6. Routing

**Publik tanpa login** (`routes/web.php`): `/` (Home), `/login`, `/daftar`, `/sitemap.xml`.

**Butuh login** (grup middleware `auth` di `routes/web.php`): `/pendahuluan`, `/petunjuk-penggunaan`, `/buku-saku[...]`, `/tutorial[...]`, `/instrumen[...]`, `/upload`, `/pencarian`, `/profil` (ubah password sendiri), `/logout`.

**Admin** (`routes/admin.php`, di-require dari `web.php` dengan prefix `admin` dan name prefix `admin.`, middleware `auth` + `admin`): `/admin` (dashboard), `/admin/pages/{slug}/edit`, `/admin/books|tutorials|instruments` (resource CRUD), `/admin/uploads[...]`, `/admin/users` (resource, super_admin only, meliputi akun staf maupun akun publik), `/admin/settings` (super_admin only), `/admin/activity-logs` (super_admin only).

Jalankan `php artisan route:list` untuk daftar lengkap dengan method dan middleware.

---

## 7. Authentication

Login (`App\Http\Controllers\AuthController`) dipakai bersama oleh staf maupun akun publik — tidak ada login terpisah untuk admin. Divalidasi lewat `App\Http\Requests\LoginRequest` (pola sama seperti Laravel Breeze): rate limiting 5 percobaan per kombinasi email+IP, lockout dengan pesan sisa waktu, dan akun `is_active=false` ditolak saat login. Pendaftaran publik (`App\Http\Controllers\RegisterController`) selalu membuat akun dengan role `user`, langsung login, dan redirect ke Home — begitu juga login staf (redirect selalu ke Home, bukan ke `/admin`; staf membuka Dashboard lewat menu Profil di navbar). Password di-hash dengan bcrypt (cast `'password' => 'hashed'` pada model `User`). Session di-regenerate saat login dan diinvalidasi saat logout. Ubah password mandiri ada di `/profil` (`App\Http\Controllers\ProfileController`), mewajibkan password saat ini benar (`current_password` rule).

---

## 8. Authorization

Middleware `admin` (`App\Http\Middleware\EnsureIsAdmin`) memastikan akun aktif (`is_active`) **dan** berperan staf (`User::isStaff()`, yaitu bukan role `user`) sebelum masuk `/admin/*`. Akun `user` yang mencoba mengakses `/admin` tidak di-logout paksa — mereka tetap sah login untuk sisi situs lain, hanya diarahkan kembali ke Home.

Policy per modul (`app/Policies/`) mengatur aksi granular:

| Policy | Aturan |
|---|---|
| `BookPolicy`, `TutorialPolicy`, `InstrumentPolicy`, `PagePolicy` | create/update/delete: `super_admin` & `admin` |
| `UploadPolicy` | update (ubah status): semua role staf; delete: `super_admin` & `admin` |
| `UserPolicy` | semua aksi: `super_admin` saja; tidak bisa menghapus akun sendiri |

Setting & Activity Log dibatasi langsung di controller (`abort_unless(auth()->user()->isSuperAdmin(), 403)`).

---

## 9. File Upload

Semua file (cover buku, file buku/tutorial/instrumen, dokumen upload publik, logo/favicon) melewati `App\Services\FileUploadService`:

- Nama file **selalu** diganti UUID acak — nama asli hanya disimpan sebagai metadata (`original_filename` pada tabel `uploads`), tidak pernah dipakai sebagai path penyimpanan.
- Validasi MIME/ekstensi **allowlist** per konteks lewat Form Request (`mimes:pdf`, `mimes:pdf,doc,docx,xls,xlsx`, dst.) — Laravel memvalidasi berdasarkan MIME hasil sniffing konten file, bukan sekadar ekstensi klaim klien, sehingga file executable yang di-rename tetap ditolak.
- Ukuran maksimum dikonfigurasi lewat `.env` (lihat `config/sisukat.php`), bukan hardcoded.
- Disimpan di disk `local` (`storage/app/private`) — **tidak** lewat symlink publik. Semua akses (preview/baca/download) melalui route controller yang mengecek keberadaan file dan status published sebelum menyajikan, sehingga path server tidak pernah terekspos ke pengguna. Pengecualian yang disengaja: logo & favicon (aset branding publik) disimpan di disk `public` + `storage:link`, karena keduanya memang ditujukan untuk diakses publik.
- Penggantian file (edit Buku/Tutorial/Instrumen) menghapus file lama **setelah** file baru berhasil tersimpan (`FileUploadService::replace()`), mencegah file yatim (orphan) maupun kehilangan file saat upload baru gagal.
- Form Upload Dokumen publik dibatasi `throttle:6,1` untuk mencegah penyalahgunaan.

---

## 10. Admin Dashboard

`/admin` menampilkan statistik (total buku/tutorial/instrumen/upload), 5 upload terbaru, dan 8 aktivitas admin terbaru. Sidebar (`resources/views/components/admin/sidebar.blade.php`) menyesuaikan menu berdasarkan role — grup **Pengguna** dan **Pengaturan** hanya tampil untuk `super_admin`.

Editor konten (Pendahuluan/Petunjuk Penggunaan/Tentang SISUKAT, serta artikel Tutorial) memakai Quill.js (lazy-loaded lewat dynamic import), dengan tombol Table kustom karena Quill tidak mendukung tabel secara native. Semua HTML dari editor disaring lewat `mews/purifier` (HTMLPurifier) sebelum disimpan — lihat whitelist tag di `config/purifier.php`.

---

## 11. Deployment (Ubuntu + Nginx)

### 11.1 Persiapan Server

```bash
sudo apt update && sudo apt install -y nginx mysql-server php8.3-fpm \
  php8.3-cli php8.3-mysql php8.3-mbstring php8.3-xml php8.3-curl \
  php8.3-gd php8.3-zip php8.3-bcmath unzip git

curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
```

### 11.2 Deploy Aplikasi

```bash
sudo mkdir -p /var/www/sisukat
sudo chown $USER:$USER /var/www/sisukat
git clone <repository-url> /var/www/sisukat
cd /var/www/sisukat

composer install --optimize-autoloader --no-dev
npm install && npm run build

cp .env.example .env
php artisan key:generate
# edit .env: APP_ENV=production, APP_DEBUG=false, APP_URL, DB_*, MAIL_*

mysql -u root -p -e "CREATE DATABASE sisukat CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan migrate --seed --force
php artisan storage:link

sudo chown -R www-data:www-data /var/www/sisukat
sudo find /var/www/sisukat -type d -exec chmod 755 {} \;
sudo find /var/www/sisukat -type f -exec chmod 644 {} \;
sudo chmod -R 775 /var/www/sisukat/storage /var/www/sisukat/bootstrap/cache

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### 11.3 Konfigurasi Nginx

`/etc/nginx/sites-available/sisukat`:

```nginx
server {
    listen 80;
    server_name sisukat.example.com;
    root /var/www/sisukat/public;

    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;

    index index.php;
    charset utf-8;

    client_max_body_size 25M;  # >= UPLOAD_DOCUMENT_MAX_KB / BOOK_FILE_MAX_KB terbesar di .env

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    # Cegah akses langsung ke file sensitif
    location ~ /\.(?!well-known).* { deny all; }
    location ~* \.(env|log|sql)$ { deny all; }
    location ~ ^/storage/app/private/ { deny all; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.ht { deny all; }
}
```

```bash
sudo ln -s /etc/nginx/sites-available/sisukat /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx
```

### 11.4 SSL (Let's Encrypt)

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d sisukat.example.com
```

### 11.5 Queue Worker (notifikasi upload dokumen)

Notifikasi email ke admin saat ada upload baru dikirim lewat queue (`QUEUE_CONNECTION=database`). Jalankan worker sebagai service systemd, `/etc/systemd/system/sisukat-queue.service`:

```ini
[Unit]
Description=SISUKAT Queue Worker
After=network.target mysql.service

[Service]
User=www-data
WorkingDirectory=/var/www/sisukat
ExecStart=/usr/bin/php artisan queue:work --sleep=3 --tries=3 --max-time=3600
Restart=always

[Install]
WantedBy=multi-user.target
```

```bash
sudo systemctl enable --now sisukat-queue
```

### 11.6 Cron (scheduler)

```
* * * * * cd /var/www/sisukat && php artisan schedule:run >> /dev/null 2>&1
```

---

## 12. Backup

**Database:**

```bash
mysqldump -u root -p sisukat | gzip > sisukat-$(date +%Y%m%d).sql.gz
```

**File upload/storage** (buku, instrumen, dokumen masuk — semua ada di `storage/app/private` dan `storage/app/public`):

```bash
tar -czf sisukat-storage-$(date +%Y%m%d).tar.gz storage/app
```

Jadwalkan keduanya lewat cron harian, simpan di lokasi terpisah dari server produksi (mis. object storage terenkripsi). Uji proses restore secara berkala — backup yang belum pernah diuji restore-nya bukan backup yang bisa diandalkan.

**Restore:**

```bash
gunzip < sisukat-YYYYMMDD.sql.gz | mysql -u root -p sisukat
tar -xzf sisukat-storage-YYYYMMDD.tar.gz -C /var/www/sisukat/
```

---

## 13. Maintenance

```bash
php artisan down --secret="some-token"   # mode maintenance, admin tetap bisa akses via /some-token
# ...deploy/migrate...
php artisan up
```

Setelah setiap deploy: `composer install --no-dev`, `npm run build`, `php artisan migrate --force`, lalu `php artisan optimize` (menjalankan config/route/view/event cache sekaligus). Setelah mengubah `.env`, jalankan `php artisan config:clear` sebelum `config:cache` ulang — konfigurasi yang di-cache tidak membaca `.env` lagi sampai di-cache ulang.

Pantau `storage/logs/laravel.log` (rotasi otomatis via driver `daily` — ubah `LOG_CHANNEL=daily` di `.env` untuk produksi) dan `failed_jobs` table untuk notifikasi upload yang gagal terkirim (`php artisan queue:failed`, `php artisan queue:retry all`).

---

## 14. Troubleshooting

| Gejala | Kemungkinan Penyebab & Solusi |
|---|---|
| Halaman blank / 500 tanpa detail | `APP_DEBUG=false` di produksi (sesuai standar keamanan) — cek `storage/logs/laravel.log` untuk stack trace asli. |
| Aset CSS/JS tidak termuat (halaman polos) | `npm run build` belum dijalankan, atau file `public/hot` tertinggal dari sesi `npm run dev` sebelumnya — hapus `public/hot` jika ada. |
| Perubahan `.env` tidak berefek | Config sudah di-cache — jalankan `php artisan config:clear` lalu `config:cache` ulang. |
| Route/tautan error "Route not defined" setelah deploy | Jalankan `php artisan route:clear` lalu `route:cache` ulang setelah menambah/mengubah route. |
| Upload gagal terus meski file valid | Cek `upload_max_filesize` & `post_max_size` di `php.ini` PHP-FPM — harus ≥ batas di `config/sisukat.php`; cek juga `client_max_body_size` di Nginx. |
| Notifikasi email upload tidak terkirim | Queue worker tidak jalan (`sudo systemctl status sisukat-queue`) atau `MAIL_*` di `.env` belum dikonfigurasi. Cek `php artisan queue:failed`. |
| Login admin gagal terus / "Terlalu banyak percobaan" | Rate limiter aktif (5x/menit per email+IP) — tunggu, atau `php artisan cache:clear` di lingkungan development. |
| File PDF tidak tampil di pembaca buku saku | Periksa Network tab browser untuk request ke `/buku-saku/{slug}/file` — pastikan file benar-benar ada di `storage/app/private/books/` dan `php artisan storage:link` sudah dijalankan (untuk aset publik lain). |
| Permission denied saat Laravel menulis log/cache | `storage/` dan `bootstrap/cache/` harus writable oleh user web server: `sudo chmod -R 775 storage bootstrap/cache && sudo chown -R www-data:www-data storage bootstrap/cache`. |

---

## Testing

```bash
php artisan test
```

Test suite (Pest) mencakup: autentikasi & rate limiting, otorisasi berbasis role, CRUD setiap modul (Buku/Tutorial/Instrumen/Upload/Admin/Pengguna), validasi & keamanan upload file (penolakan tipe file terlarang, nama file acak, batas ukuran), penghitung unduhan, sanitasi XSS pada rich-text editor, pencarian global, dan halaman error kustom.
