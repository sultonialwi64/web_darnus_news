# 🗞️ DarnusNews — Portal Berita Modern

DarnusNews adalah aplikasi portal berita yang dibangun dengan:
- **Laravel 13** sebagai backend framework
- **Filament v3** sebagai Admin Dashboard
- **Tailwind CSS v4** untuk tampilan publik yang modern dan responsif
- **MySQL** sebagai database

---

## ✨ Fitur

- Portal berita publik bergaya jurnalistik (seperti qz.com)
- Artikel Featured (besar), Latest News, dan Trending Now di halaman depan
- Pencarian berita dinamis
- Tracking jumlah pembaca (views) otomatis
- Manajemen Berita, Kategori, dan Wilayah via Admin Panel Filament
- Seed data berita Indonesia siap pakai

---

## 🖥️ Instalasi Lokal (Development)

### Persyaratan
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL

### Langkah-langkah

```bash
# 1. Clone atau ekstrak project
cd web-berita-darnus-news

# 2. Install dependency PHP
composer install

# 3. Salin file environment
cp .env.example .env

# 4. Generate app key
php artisan key:generate

# 5. Sesuaikan koneksi database di file .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=darnus_db
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Jalankan migrasi + seeder (membuat tabel & isi data dummy)
php artisan migrate:fresh --seed

# 7. Buat symlink storage (agar gambar bisa diakses publik)
php artisan storage:link

# 8. Install & build frontend
npm install
npm run build

# 9. Jalankan server
php artisan serve
```

Akses di: `http://localhost:8000`
Admin Panel: `http://localhost:8000/admin`
- **Email**: `admin@darnusnews.com`
- **Password**: `admin123`

---

## 🚀 Panduan Deployment ke Shared Hosting (Rumahweb, tanpa SSH)

### Persiapan di Laptop (Wajib sebelum Upload)

**Step 1 — Build frontend (WAJIB)**
```bash
npm run build
```
Pastikan folder `public/build/` sudah terisi sebelum upload.

**Step 2 — Sesuaikan file `.env` untuk production**

Buka file `.env` dan ubah nilai-nilai berikut:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domainanda.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=nama_database_di_cpanel
DB_USERNAME=username_db_di_cpanel
DB_PASSWORD=password_db_di_cpanel

DEPLOY_TOKEN=GANTI_TOKEN_RAHASIA_ANDA
```

> ⚠️ **Penting!** Ganti `DEPLOY_TOKEN` dengan kata rahasia pilihan Anda sendiri. Token ini digunakan untuk mengamankan URL setup server.

---

### Upload ke Hosting

**Step 3 — Upload via GitHub (Rekomendasi)**

1. Buat **Private Repository** di GitHub.
2. Push seluruh kode project:
   ```bash
   git init
   git add .
   git commit -m "Initial deployment"
   git remote add origin https://github.com/username/repo.git
   git push -u origin main
   ```
3. Login ke **cPanel Rumahweb**.
4. Cari menu **"Git Version Control"**.
5. Klik **"Create"**, masukkan URL repository GitHub Anda.
6. Pastikan direktori tujuan adalah `/home/namauser/` *(satu level di atas `public_html`)*.
7. Klik **"Clone"** atau **"Pull"** untuk mengunduh kode ke server.

> 📁 Struktur folder di server harus seperti ini:
> ```
> /home/namauser/
> ├── web-berita-darnus-news/   ← folder project laravel
> │   ├── app/
> │   ├── public/
> │   ├── ...
> └── public_html/              ← domain utama mengarah ke sini
> ```

**Step 4 — Atur Document Root di cPanel**

Agar domain mengarah ke folder `public` Laravel:
1. Di cPanel, cari **"Domains"** atau **"Addon Domains"**.
2. Klik **"Manage"** pada domain Anda.
3. Ubah **Document Root** dari `public_html` menjadi:
   `public_html/web-berita-darnus-news/public`
   *(atau sesuai letak folder project Anda)*

**Step 5 — Buat Database di cPanel**

1. Di cPanel, buka **"MySQL Databases"**.
2. Buat database baru (catat namanya).
3. Buat user database baru (catat username & password).
4. Hubungkan user ke database dengan memberikan **All Privileges**.
5. Sesuaikan `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` di file `.env` dengan data yang baru dibuat ini.

---

### Setup Server (Tanpa SSH)

**Step 6 — Jalankan Setup via Browser**

Setelah upload selesai, buka URL berikut di browser:
```
https://domainanda.com/setup-server?token=TOKEN_RAHASIA_ANDA
```

URL ini akan otomatis menjalankan:
- ✅ `storage:link` — Menghubungkan folder storage ke public
- ✅ `migrate` — Membuat semua tabel di database
- ✅ `db:seed` — Membuat akun admin
- ✅ `optimize:clear` — Membersihkan cache

> ⚠️ **Setelah setup selesai, segera hapus atau komentari route `/setup-server`** di file `routes/web.php` demi keamanan!

---

### Akses Website

Setelah semua langkah selesai:
- **Website Publik**: `https://domainanda.com`
- **Admin Panel**: `https://domainanda.com/admin`
  - Email: `admin@darnusnews.com`
  - Password: `admin123`

> 🔐 Segera ganti password admin setelah login pertama kali!

---

## 📁 Arsitektur Folder

```
app/
├── Http/Controllers/
│   └── HomeController.php     ← Controller halaman publik
├── Models/
│   ├── Post.php               ← Model berita
│   ├── Category.php           ← Model kategori
│   └── Region.php             ← Model wilayah
└── Filament/Resources/
    ├── Posts/
    │   ├── PostResource.php
    │   ├── Schemas/PostForm.php    ← Konfigurasi form tambah berita
    │   └── Tables/PostsTable.php  ← Konfigurasi tabel daftar berita
    ├── Categories/
    └── Regions/

resources/views/
├── welcome.blade.php          ← Halaman depan (Featured, Latest, Trending)
└── news/
    ├── show.blade.php         ← Halaman baca artikel
    └── search.blade.php       ← Halaman hasil pencarian

database/
├── migrations/                ← Struktur tabel database
├── factories/                 ← Generator data dummy
└── seeders/
    └── DatabaseSeeder.php     ← Konfigurasi data awal
```

---

## 📝 Update Kode (Setelah Sudah Live)

Setiap ada perubahan kode:
1. Jalankan `npm run build` di laptop jika ada perubahan tampilan.
2. Push ke GitHub: `git add . && git commit -m "update" && git push`
3. Di cPanel → **Git Version Control** → **Pull**

---

## 📄 Lisensi
Proyek ini open-source di bawah lisensi MIT.
