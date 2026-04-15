# 🗞️ DarnusNews — Portal Berita Indonesia

> **Dokumentasi ini dibuat sebagai panduan lengkap untuk developer/AI agent berikutnya yang melanjutkan proyek ini.**

---

## 📋 Daftar Isi
1. [Ringkasan Proyek](#-ringkasan-proyek)
2. [Tech Stack](#-tech-stack)
3. [Arsitektur & Struktur Folder](#-arsitektur--struktur-folder)
4. [Database Schema](#-database-schema)
5. [Fitur yang Sudah Ada](#-fitur-yang-sudah-ada)
6. [Kredensial Penting](#-kredensial-penting)
7. [Setup Development Lokal](#-setup-development-lokal)
8. [Panduan Deployment ke Shared Hosting](#-panduan-deployment-ke-shared-hosting)
9. [Cara Update Server (Workflow Harian)](#-cara-update-server-workflow-harian)
10. [Deployment Bridge (setup-server)](#-deployment-bridge-setup-server)
11. [Catatan Teknis Penting](#-catatan-teknis-penting)
12. [Roadmap & Yang Belum Selesai](#-roadmap--yang-belum-selesai)

---

## 🎯 Ringkasan Proyek

DarnusNews adalah portal berita Indonesia berbasis web dengan desain editorial gelap bergaya jurnalistik internasional (terinspirasi dari Süddeutsche Zeitung). Dibangun untuk deployment di shared hosting tanpa akses SSH.

- **Repository GitHub:** `https://github.com/sultonialwi64/web_darnus_news`
- **Domain:** `https://darnusnews.com`
- **Hosting:** Rumahweb (Shared Hosting, tanpa SSH)
- **cPanel Username:** `dary8498`
- **Lokasi file di server:** `/home/dary8498/public_html/` (semua file Laravel ada di sini)

---

## ⚙️ Tech Stack

| Layer | Teknologi | Versi |
|---|---|---|
| Backend Framework | Laravel | ^13.0 |
| Admin Panel | Filament | ^5.5 |
| Frontend CSS | Tailwind CSS | ^4.0 |
| Build Tool | Vite | ^8.0 |
| Database | MySQL | - |
| PHP | PHP | ^8.3 |
| Package Manager (PHP) | Composer | - |
| Package Manager (JS) | NPM | - |
| Lokal Dev Server | Laravel Herd (Windows) | - |

### Package Frontend (devDependencies)
```json
{
    "@tailwindcss/vite": "^4.0.0",
    "axios": ">=1.11.0 <=1.14.0",
    "concurrently": "^9.0.1",
    "laravel-vite-plugin": "^3.0.0",
    "tailwindcss": "^4.0.0",
    "vite": "^8.0.0"
}
```

### Package Backend (require)
```json
{
    "php": "^8.3",
    "filament/filament": "^5.5",
    "laravel/framework": "^13.0",
    "laravel/tinker": "^3.0"
}
```

---

## 🗂️ Arsitektur & Struktur Folder

```
web-berita-darnus-news/
│
├── app/
│   ├── Http/Controllers/
│   │   └── HomeController.php        ← Controller untuk semua halaman publik
│   │                                   (index, show, search)
│   │
│   ├── Models/
│   │   ├── Post.php                  ← Model berita (belongsTo Region, Category)
│   │   ├── Category.php              ← Model kategori
│   │   ├── Region.php                ← Model wilayah (kota/daerah)
│   │   └── User.php                  ← Model user (implements FilamentUser)
│   │
│   ├── Filament/Resources/
│   │   ├── Posts/
│   │   │   ├── PostResource.php
│   │   │   ├── Schemas/PostForm.php  ← Form untuk tambah/edit berita
│   │   │   └── Tables/PostsTable.php ← Tampilan tabel daftar berita
│   │   ├── Categories/
│   │   │   └── CategoryResource.php (+ Schemas, Tables)
│   │   └── Regions/
│   │       └── RegionResource.php (+ Schemas, Tables)
│   │
│   └── Providers/Filament/
│       └── AdminPanelProvider.php    ← Konfigurasi panel admin Filament
│                                       (warna Amber/Gold, dark mode, brand name)
│
├── database/
│   ├── migrations/
│   │   ├── ...create_users_table.php
│   │   ├── 2026_04_12_060005_create_regions_table.php
│   │   ├── 2026_04_12_060006_create_categories_table.php
│   │   └── 2026_04_12_060014_create_posts_table.php
│   │
│   ├── factories/
│   │   ├── PostFactory.php           ← Data dummy berita Indonesia realistis (30 judul)
│   │   ├── CategoryFactory.php       ← kategori: Ekonomi, Olahraga, Kesehatan, dll
│   │   └── RegionFactory.php         ← kota-kota Indonesia
│   │
│   └── seeders/
│       └── DatabaseSeeder.php        ← Seed: 1 admin + 5 kategori + 5 region + 30 posts
│
├── resources/
│   ├── css/app.css                   ← Entry point Tailwind CSS
│   └── views/
│       ├── welcome.blade.php         ← Halaman beranda (Featured + 4 Latest + Trending)
│       └── news/
│           ├── show.blade.php        ← Halaman baca artikel
│           └── search.blade.php      ← Halaman hasil pencarian
│
├── routes/
│   └── web.php                       ← Route publik + Deployment Bridge
│
├── public/
│   ├── build/                        ← Hasil build Vite (CSS + JS di-compile)
│   │   ├── manifest.json
│   │   └── assets/
│   │       ├── app-[hash].css
│   │       └── app-[hash].js
│   └── .htaccess                     ← Konfigurasi Apache untuk Laravel
│
├── storage/
│   └── app/
│       └── public/
│           └── posts/                ← Foto-foto yang diupload admin
│               └── dummy_news.jpg    ← Foto placeholder untuk berita dummy
│
├── .htaccess                         ← Root .htaccess (redirect ke /public)
├── .env                              ← Konfigurasi environment (JANGAN di-commit!)
└── .gitignore                        ← Termasuk: *.zip, /vendor, /node_modules
```

---

## 🗄️ Database Schema

### Tabel `users`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint | Primary Key |
| name | varchar | Nama admin |
| email | varchar | Email (unique) |
| password | varchar | Bcrypt hashed |
| timestamps | - | created_at, updated_at |

### Tabel `regions`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint | Primary Key |
| name | varchar | Nama kota/wilayah |
| slug | varchar | URL-friendly name |
| timestamps | - | - |

### Tabel `categories`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint | Primary Key |
| name | varchar | Nama kategori |
| slug | varchar | URL-friendly name |
| timestamps | - | - |

### Tabel `posts`
| Kolom | Tipe | Default | Keterangan |
|---|---|---|---|
| id | bigint | - | Primary Key |
| region_id | bigint (FK) | - | Relasi ke `regions` (cascadeOnDelete) |
| category_id | bigint (FK) | - | Relasi ke `categories` (cascadeOnDelete) |
| title | varchar | - | Judul berita |
| slug | varchar (unique) | - | URL berita |
| content | text | - | Isi berita (HTML dari RichEditor) |
| image | varchar | null | Path foto relatif ke storage/public |
| is_published | boolean | false | Toggle publish/draft |
| views | unsigned int | 0 | Counter pembaca (auto-increment saat dibuka) |
| timestamps | - | - | created_at, updated_at |

---

## ✨ Fitur yang Sudah Ada

### Halaman Publik
- **Beranda (`/`)**: Featured article besar (berita terbaru), 4 "Berita Terkini", daftar artikel + pagination, sidebar "Paling Banyak Dibaca"
- **Detail Berita (`/news/{slug}`)**: Halaman baca artikel penuh, foto featured, info kategori & wilayah, view counter
- **Pencarian (`/search?q=...`)**: Grid hasil pencarian dengan pagination

### Admin Panel (`/admin`)
- **CRUD Berita**: Tambah, edit, hapus berita; rich text editor; upload foto; toggle publish
- **CRUD Kategori**: Kelola kategori berita
- **CRUD Wilayah**: Kelola wilayah/kota
- **Dark Mode**: Panel admin dark mode dengan warna Gold/Amber
- **Brand**: Panel berlabel "DarnusNews"

### Teknis
- **Auto View Count**: Setiap artikel dibuka, kolom `views` otomatis +1
- **Storage Public**: `FILESYSTEM_DISK=public` → foto tersimpan di `storage/app/public/posts/`
- **Deployment Bridge**: Route `/setup-server` untuk setup server tanpa SSH
- **Dummy Data**: Seeder siap pakai dengan berita Indonesia realistis

---

## 🔑 Kredensial Penting

### Admin Dashboard
- **URL**: `https://darnusnews.com/admin` (atau `http://localhost:8000/admin` lokal)
- **Email**: `admin@darnusnews.com`
- **Password**: `admin123`

> ⚠️ **Segera ganti password ini setelah login pertama di server baru!**

### Deployment Bridge
- **URL**: `https://darnusnews.com/setup-server?token=DarnusSetup2025`
- **Token**: `DarnusSetup2025` (diset via `DEPLOY_TOKEN` di `.env`)

### Database Aktif di Hosting (Rumahweb)
- **Host**: `localhost`
- **Database**: `dary8498_darnus_db`
- **Username**: `dary8498_darnus`
- **Password**: `wonosobo77`

> ⚠️ Untuk hosting baru, buat database baru dan update nilai-nilai ini di `.env` server.

---

## 💻 Setup Development Lokal

### Persyaratan
- PHP ^8.3 (direkomendasikan via Laravel Herd untuk Windows)
- Composer
- Node.js & NPM
- MySQL

### Langkah Setup

```bash
# 1. Clone repository
git clone https://github.com/sultonialwi64/web_darnus_news.git
cd web_darnus_news

# 2. Install PHP dependencies
composer install

# 3. Install JS dependencies
npm install

# 4. Salin file environment
cp .env.example .env

# 5. Generate app key
php artisan key:generate

# 6. Sesuaikan .env untuk lokal:
#    DB_CONNECTION=mysql
#    DB_HOST=127.0.0.1
#    DB_DATABASE=darnus_db
#    DB_USERNAME=root
#    DB_PASSWORD=
#    FILESYSTEM_DISK=public      ← WAJIB, jangan pakai "local"
#    APP_URL=http://127.0.0.1:8000

# 7. Buat database kosong di MySQL, kemudian jalankan:
php artisan migrate:fresh --seed

# 8. Buat symlink storage
php artisan storage:link

# 9. Build CSS & JS
npm run build

# 10. Jalankan server
php artisan serve
```

**Akses:**
- Website: `http://127.0.0.1:8000`
- Admin: `http://127.0.0.1:8000/admin` → `admin@darnusnews.com` / `admin123`

### Development Mode (Hot Reload)
```bash
# Terminal 1
php artisan serve

# Terminal 2 (opsional, untuk hot reload CSS)
npm run dev
```

> ⚠️ **Catatan Windows Herd:** Kalau pakai Herd, APP_URL harus `http://127.0.0.1:8000` bukan domain lain, agar asset CSS/JS terbaca dengan benar.

---

## 🚀 Panduan Deployment ke Shared Hosting

> Panduan ini untuk shared hosting **TANPA SSH** (seperti Rumahweb paket murah).

### Persiapan di Laptop (Wajib!)

**Step 1 — Build CSS/JS**
```bash
npm run build
```
Pastikan folder `public/build/` sudah terisi.

**Step 2 — Commit & Push ke GitHub**
```bash
git add .
git commit -m "deskripsi perubahan"
git push origin main
```

### Upload ke Server

Karena tidak ada SSH/Git di cPanel, update server dilakukan manual via **File Manager cPanel**.

**Cara yang Terbukti Bekerja:**

Ada dua cara:

**Cara A — Edit Langsung (untuk update 1-2 file)**
1. Buka **File Manager cPanel** → masuk `public_html`
2. Navigasi ke file yang ingin diubah
3. Klik file → klik **Edit** di toolbar
4. Copy-paste isi file terbaru dari laptop
5. Klik **Save Changes**

**Cara B — Upload ZIP (untuk update banyak file)**

> ⚠️ **PENTING:** Struktur di dalam ZIP harus mengikuti struktur RELATIF terhadap `public_html`. Buat ZIP menggunakan `git archive` di laptop:

```bash
# Buat ZIP dari file yang berubah (contoh)
git archive --format=zip -o update.zip HEAD \
    resources/views/welcome.blade.php \
    resources/views/news/show.blade.php \
    routes/web.php \
    public/build
```

Kemudian di cPanel:
1. Masuk ke folder **`public_html`** dulu di File Manager
2. Upload ZIP ke dalam `public_html/`
3. Klik kanan ZIP → **Extract**
4. File akan langsung masuk ke lokasi yang benar

> ⚠️ **Jangan extract di root (`/home/dary8498/`)**! Harus dari dalam `public_html/`.

### Setup Database & Konfigurasi Server Baru

**Step 1 — Buat Database di cPanel**
1. MySQL Databases → buat database baru
2. Buat user baru + hubungkan ke database (All Privileges)
3. Catat: nama DB, username, password

**Step 2 — Buat/Edit file `.env` di server**

Masuk `public_html/` → cari file `.env` → Edit:
```env
APP_NAME="DarnusNews"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...         ← generate dengan: php artisan key:generate (lokal)
APP_URL=https://domainbaru.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=nama_db_baru
DB_USERNAME=user_db_baru
DB_PASSWORD=password_db_baru

FILESYSTEM_DISK=public     ← WAJIB "public", bukan "local"!

DEPLOY_TOKEN=RahasiaAnda2025
```

**Step 3 — Jalankan Setup via Browser**

Akses URL ini sekali:
```
https://domainbaru.com/setup-server?token=RahasiaAnda2025
```

Ini akan otomatis:
- ✅ Membuat symlink `storage:link`
- ✅ Menjalankan semua migrasi database
- ✅ Membersihkan cache
- ✅ Memindahkan foto dari `private` ke `public`
- ✅ Download foto dummy & memasangnya ke berita yang belum punya foto

---

## 🔄 Cara Update Server (Workflow Harian)

Setiap ada perubahan kode di laptop:

```bash
# 1. (Jika ada perubahan CSS/JS) Rebuild asset
npm run build

# 2. Commit & push ke GitHub
git add .
git commit -m "deskripsi perubahan"
git push origin main
```

Kemudian di server:
- **Hanya ubah blade/PHP**: Edit langsung via File Manager cPanel
- **Ada perubahan CSS/build**: Upload & extract ZIP berisi `public/build/`

---

## 🛠️ Deployment Bridge (setup-server)

File: `routes/web.php`

Route khusus untuk setup server tanpa SSH. **Jangan hapus route ini** selama masih dibutuhkan untuk maintenance.

**URL:** `https://domain.com/setup-server?token=[DEPLOY_TOKEN]`

**Yang dilakukan saat diakses:**
1. `storage:link` — membuat symlink folder storage
2. `migrate` — jalankan migrasi database yang belum berjalan
3. `optimize:clear` — bersihkan semua cache Laravel
4. Pindah foto dari `private/posts` → `public/posts` (fix upload lama)
5. Download foto dummy dari Unsplash & pasang ke semua berita tanpa foto

**Token:** Diset via variabel `DEPLOY_TOKEN` di `.env`. Default: `DarnusSetup2025`.

---

## 📝 Catatan Teknis Penting

### 1. FILESYSTEM_DISK WAJIB = "public"
Laravel 11+ menggunakan disk `local` secara default yang menyimpan file di `storage/app/private/` (tidak bisa diakses publik). Pastikan **selalu** set `FILESYSTEM_DISK=public` di `.env`, baik lokal maupun server.

### 2. Struktur Hosting Tidak Standar
File Laravel ada di `public_html/` (bukan di folder tersendiri di luar public_html). Ini berarti:
- Document root Apache = `public_html/` (bukan `public_html/public/`)
- Ada 2 file `.htaccess`:
  - `public_html/.htaccess` → redirect semua request ke `/public` (Laravel public folder)
  - `public_html/public/.htaccess` → konfigurasi standar Laravel

### 3. FilamentUser Interface
`User` model wajib implement `FilamentUser` agar bisa akses admin panel di production:
```php
class User extends Authenticatable implements \Filament\Models\Contracts\FilamentUser
{
    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        return true;
    }
}
```

### 4. Git di cPanel Tidak Bisa Digunakan
Karena paket hosting tidak memberikan akses Shell, fitur Git Version Control di cPanel tidak berfungsi. Update server dilakukan manual (lihat bagian Workflow Harian).

### 5. View Count
View count otomatis bertambah setiap artikel dibuka. Logic ada di `HomeController::show()`:
```php
$post->increment('views');
```

### 6. Foto Dummy Server
File foto dummy untuk berita seeder berada di:  
`storage/app/public/posts/dummy_news.jpg`  
File ini tidak di-commit ke Git. Deployment Bridge akan mengunduhnya otomatis dari Unsplash saat pertama kali setup server baru.

---

## 🗺️ Roadmap & Yang Belum Selesai

### Fitur yang Sudah Diusulkan (Belum Diimplementasi)
- [ ] **Placement/Layout selector di dashboard** — Admin bisa pilih apakah berita tampil sebagai "Featured", "Latest", atau "Standard" (saat ini otomatis berdasarkan waktu terbaru)
- [ ] **Ganti password admin** — Belum ada UI untuk ganti password (harus via database atau Tinker)
- [ ] **Kategori bisa diklik** — Nav kategori belum terfilter, masih link `#`
- [ ] **Halaman profil redaksi/tentang kami** — Belum ada halaman statis
- [ ] **Iklan banner** — Ada placeholder "Ruang Iklan Tersedia", belum ada sistem manajemen iklan

### Known Issues
- Foto berita seeder tidak ada di server secara default → perlu run `/setup-server` setiap deploy ke server baru
- Pagination masih menggunakan style default Laravel (belum disesuaikan dengan tema dark)

---

## 📄 Lisensi
Proyek pribadi — DarnusNews © 2026. Hak Cipta Dilindungi.
