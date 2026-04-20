<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Region;
use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     * NOTE: This seeder uses direct inserts (no Faker) so it works
     * on production servers where --no-dev removes fakerphp/faker.
     */
    public function run(): void
    {
        // 1. Admin & Editor Users
        User::create([
            'name' => 'Admin Darnus',
            'email' => 'admin@darnusnews.com',
            'email_verified_at' => now(),
            'password' => bcrypt(env('DEFAULT_ADMIN_PASSWORD', 'admin123')),
            'remember_token' => Str::random(10),
        ]);

        $editors = ['Aris', 'Asri Bening', 'Wawan'];
        foreach ($editors as $editor) {
            $emailPref = strtolower(str_replace(' ', '', $editor));
            User::create([
                'name' => $editor,
                'email' => "{$emailPref}@darnusnews.com",
                'email_verified_at' => now(),
                'password' => bcrypt(env('DEFAULT_EDITOR_PASSWORD', 'editor123')),
                'remember_token' => Str::random(10),
            ]);
        }

        // 2. Categories
        $categories = [
            'Nasional', 'Daerah', 'Ekonomi', 'Opini', 'Humaniora', 
            'Sastra Budaya', 'Politik', 'Olahraga', 'Lifestyle', 
            'Pariwisata dan Kuliner', 'Hukum dan Kriminal'
        ];
        foreach ($categories as $cat) {
            Category::create([
                'name' => $cat,
                'slug' => Str::slug($cat),
            ]);
        }

        // 3. Regions
        $regions = ['Jakarta', 'Bandung', 'Surabaya', 'Makassar', 'Yogyakarta'];
        foreach ($regions as $reg) {
            Region::create([
                'name' => $reg,
                'domain' => Str::slug($reg) . '.darnusnews.com',
                'is_active' => true,
            ]);
        }

        // 4. Posts (30 berita dummy)
        $titles = [
            'Pemerintah Umumkan Program Beasiswa Nasional untuk 50.000 Mahasiswa',
            'Harga BBM Non-Subsidi Turun, Masyarakat Sambut Positif',
            'Timnas Indonesia Menang Telak 3-0 atas Malaysia di Kualifikasi Piala Dunia',
            'KPK Tetapkan Tersangka Baru Kasus Korupsi Dana Desa',
            'Banjir Bandang Terjang Wilayah Pesisir, Ratusan Warga Dievakuasi',
            'Rupiah Menguat ke Level Rp 15.400 per Dolar AS',
            'Startup Lokal Berhasil Raih Pendanaan Seri B Senilai 50 Juta Dolar',
            'Kemenkes Luncurkan Aplikasi Kesehatan Gratis untuk Seluruh Warga',
            'DPR Sahkan Undang-Undang Perlindungan Data Pribadi',
            'Festival Budaya Nusantara Akan Digelar di Jakarta Bulan Depan',
            'Polisi Ungkap Jaringan Penipuan Online Berkedok Investasi Kripto',
            'Menteri BUMN: Proyek Kereta Cepat Jakarta-Surabaya Mulai Tahun Depan',
            'Universitas Indonesia Masuk 10 Besar Perguruan Tinggi Terbaik Asia Tenggara',
            'BPOM Temukan Kosmetik Ilegal Mengandung Merkuri di Pasaran',
            'Petani di Jawa Tengah Keluhkan Anjloknya Harga Gabah',
            'Presiden Resmikan Jalan Tol Trans-Sumatera Segmen Terbaru',
            'Dua Warga Negara Asing Ditangkap atas Kasus Penyelundupan Narkoba',
            'PSSI Umumkan Pelatih Baru Timnas Senior Indonesia',
            'Gempa Bumi 5,6 SR Guncang Sulawesi Tengah, Tidak Ada Korban Jiwa',
            'Pemerintah Targetkan Pertumbuhan Ekonomi 5,5 Persen di Tahun Ini',
            'Aksi Demo Buruh Tuntut Kenaikan UMR Se-Jabodetabek',
            'Inovasi Anak Bangsa: Robot Pertanian Karya Mahasiswa ITB Raih Penghargaan',
            'Kebakaran Hutan Meluas di Kalimantan, Udara Mulai Tidak Sehat',
            'Bank Indonesia Pertahankan Suku Bunga Acuan di Level 6,25 Persen',
            'Liga 1: Persib Bandung Puncaki Klasemen Usai Taklukkan Arema',
            'Menparekraf Dorong Pengembangan Wisata Halal di NTB',
            'Kasus DBD Meningkat di Musim Hujan, Dinkes Minta Warga Waspada',
            'Pemerintah Percepat Program 3 Juta Rumah untuk Warga Berpenghasilan Rendah',
            'TikTok Shop Resmi Beroperasi Kembali dengan Skema Baru',
            'Pemkot Surabaya Larang Penggunaan Kantong Plastik Mulai Juli Mendatang',
        ];

        $paragraphs = [
            'Pemerintah pusat melalui kementerian terkait telah resmi mengumumkan kebijakan baru yang diharapkan dapat memberikan dampak positif bagi masyarakat luas.',
            'Kepala Dinas setempat menyatakan bahwa pihaknya siap mendukung penuh implementasi program tersebut.',
            'Sejumlah pengamat menilai kebijakan ini merupakan langkah maju yang patut diapresiasi.',
            'Warga yang ditemui di lapangan umumnya menyambut positif kabar ini.',
            'Data terakhir yang dirilis lembaga terkait menunjukkan adanya tren positif dalam beberapa bulan terakhir.',
        ];

        $categoryIds = Category::pluck('id')->toArray();
        $regionIds = Region::pluck('id')->toArray();

        foreach ($titles as $i => $title) {
            $content = collect($paragraphs)->map(fn($p) => "<p>$p</p>")->implode("\n");

            Post::create([
                'region_id'    => $regionIds[$i % count($regionIds)],
                'category_id'  => $categoryIds[$i % count($categoryIds)],
                'title'        => $title,
                'slug'         => Str::slug($title) . '-' . ($i + 1),
                'content'      => $content,
                'image'        => null,
                'is_published' => true,
                'views'        => ($i + 1) * 137,
                'created_at'   => now()->subDays(30 - $i),
            ]);
        }
    }
}
