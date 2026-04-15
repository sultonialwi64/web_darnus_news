# 🗞️ DarnusNews — Portal Berita Indonesia

> **DOKUMENTASI HANDOFF:** Panduan lengkap untuk developer/AI agent berikutnya yang melanjutkan proyek ini.

---

## 📋 Daftar Isi
1. [Ringkasan Proyek](#-ringkasan-proyek)
2. [Tech Stack](#-tech-stack)
3. [Arsitektur & Struktur Folder](#-arsitektur--struktur-folder)
4. [Database Schema](#-database-schema)
5. [Fitur Utama](#-fitur-utama)
6. [Kredensial Penting](#-kredensial-penting)
7. [Setup Development Lokal](#-setup-development-lokal)
8. [Panduan Deployment (Domainesia SSH)](#-panduan-deployment-domainesia-ssh)
9. [Catatan Penting AI Agent](#-catatan-penting-ai-agent)

---

## 🎯 Ringkasan Proyek

DarnusNews adalah portal berita Indonesia dengan desain editorial gelap bergaya jurnalistik internasional. Proyek ini dioptimalkan untuk performa tinggi dan pengelolaan yang profesional.

- **Repository GitHub:** `https://github.com/sultonialwi64/web_darnus_news`
- **Domain:** `https://darnusnews.com`
- **Hosting Partner:** Domainesia (Premium Hosting dengan akses Terminal/SSH)
- **Workflow:** Local (Git Push) → GitHub → Server (Git Pull)

---

## ⚙️ Tech Stack

| Layer | Teknologi | Versi |
|---|---|---|
| Backend Framework | Laravel | ^11.0 / 13.0 |
| Admin Panel | Filament | ^3.0 / v5.5 |
| Frontend CSS | Tailwind CSS | ^4.0 |
| Build Tool | Vite | ^8.0 |
| PHP Runtime | PHP | ^8.3 |

---

## 🗂️ Arsitektur & Struktur Folder

- `app/Http/Controllers/HomeController.php`: Logic utama untuk Beranda, Detail, dan Search.
- `app/Models/Post.php`: Model berita (memiliki counter `views`).
- `database/seeders/DatabaseSeeder.php`: **PENTING!** Seeder ini dibuat tanpa Faker agar bisa berjalan aman di production tanpa package dev.
- `resources/views/`: Semua file Blade (welcome, show, search) sudah menggunakan standar editorial profesional.
- `routes/web.php`: Berisi rute publik dan `Deployment Bridge` untuk automasi.

---

## ✨ Fitur Utama

- **Editorial Dark Theme:** Desain premium terinspirasi Süddeutsche Zeitung.
- **Professional Photo Standards:** Jika berita tidak memiliki foto, sistem **tidak akan** menampilkan kotak gambar sama sekali (layout otomatis merapat).
- **Auto View Counter:** Menghitung jumlah pembaca secara otomatis setiap kali artikel dibuka.
- **Admin Dashboard:** Berbasi Filament v3 dengan branding penuh "DarnusNews".
- **Deployment Bridge:** Akses `domain.com/setup-server?token=[TOKEN]` untuk maintenance cepat.

---

## 🔑 Kredensial Penting

### Admin Dashboard
- **URL**: `https://darnusnews.com/admin`
- **Email**: `admin@darnusnews.com`
- **Password**: `admin123`

### Deployment Bridge
- **URL**: `/setup-server?token=DarnusSetup2025`
- **Fungsi**: Storage link, Migration, Clear Cache, & Asset Fixing.

---

## 🚀 Panduan Deployment (Domainesia SSH)

Gunakan alur kerja profesional ini di terminal server:

```bash
# Masuk ke folder proyek
cd ~/darnusnews

# Tarik update terbaru dari GitHub
git pull origin main

# Optimasi (Wajib dijalankan setiap update)
php artisan optimize
```

**Jika baru pertama kali install di server baru:**
1. Clone repo di root server.
2. Setup `.env` (pastikan DB credentials benar).
3. Jalankan `composer install --optimize-autoloader --no-dev`.
4. Jalankan `php artisan migrate:fresh --seed --force`.
5. Jalankan `php artisan storage:link`.

---

## 🤖 Catatan Penting AI Agent (READ THIS!)

1.  **FILESYSTEM_DISK:** Harus selalu `public` di `.env`. Jangan gunakan `local`.
2.  **No-Faker Policy:** Di server production, package `fakerphp/faker` tidak terinstall (karena `--no-dev`). Gunakan `DatabaseSeeder.php` yang sudah ada jika ingin mengisi data dummy, jangan pakai `factory()`.
3.  **Image Fallback:** Jangan menggunakan auto-random photo dari internet (Unsplash, dll). Atas permintaan user, jika foto tidak ada, blok gambar harus di-hide sepenuhnya.
4.  **Herd Local Dev:** Jika dev lokal menggunakan Windows + Herd, pastikan `APP_URL=http://127.0.0.1:8000` agar Vite asset terbaca.
5.  **Deployment Bridge:** Kode bridge di `web.php` sangat berguna untuk memperbaiki masalah folder storage tanpa harus masuk SSH jika mendesak.

---

## 🗺️ Roadmap Selanjutnya
- [ ] Implementasi filter berita berdasarkan Kategori yang bisa diklik.
- [ ] Penambahan sistem Manajemen Iklan (Ad Management).
- [ ] Halaman Profil Redaksi & Kontak.

---
**DarnusNews © 2026. Made with ❤️ for Professional Journalism.**
