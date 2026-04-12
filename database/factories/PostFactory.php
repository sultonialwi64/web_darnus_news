<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    // Kumpulan judul berita Indonesia yang realistis
    protected static array $titles = [
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

    // Kumpulan paragraf konten berita Indonesia
    protected static array $paragraphs = [
        'Pemerintah pusat melalui kementerian terkait telah resmi mengumumkan kebijakan baru yang diharapkan dapat memberikan dampak positif bagi masyarakat luas. Langkah ini diambil setelah melalui serangkaian kajian mendalam dan konsultasi publik yang melibatkan berbagai pemangku kepentingan.',
        'Kepala Dinas setempat menyatakan bahwa pihaknya siap mendukung penuh implementasi program tersebut. "Kami akan memastikan seluruh elemen masyarakat di daerah ini mendapatkan manfaat yang optimal dari kebijakan ini," ujarnya dalam konferensi pers yang digelar Kamis sore.',
        'Sejumlah pengamat menilai kebijakan ini merupakan langkah maju yang patut diapresiasi. Namun demikian, mereka juga mengingatkan agar pemerintah memastikan pengawasan yang ketat dalam proses pelaksanaannya agar tidak terjadi penyimpangan.',
        'Warga yang ditemui di lapangan umumnya menyambut positif kabar ini. Ahmad (45), seorang pedagang kaki lima, mengaku berharap kebijakan tersebut benar-benar dapat dirasakan manfaatnya oleh kalangan bawah. "Yang penting harga-harga kebutuhan pokok ikut stabil," katanya.',
        'Data terakhir yang dirilis lembaga terkait menunjukkan adanya tren positif dalam beberapa bulan terakhir. Meski demikian, para ahli mengingatkan bahwa masih diperlukan kerja keras dan konsistensi untuk mempertahankan capaian ini di tengah dinamika global yang terus berubah.',
        'Pihak kepolisian daerah turut mengkonfirmasi bahwa situasi di wilayah tersebut saat ini terpantau kondusif. Aparat keamanan terus berjaga dan melakukan patroli rutin untuk memastikan ketertiban serta keamanan masyarakat tetap terjaga dengan baik.',
        'Dalam sidang paripurna yang berlangsung selama hampir tiga jam, seluruh fraksi di DPR menyatakan dukungannya terhadap rancangan peraturan yang diajukan. Proses pembahasan dinilai berjalan alot namun akhirnya menemui kata sepakat yang memuaskan semua pihak.',
        'Tim investigasi gabungan yang telah bekerja selama dua bulan terakhir akhirnya menemukan titik terang dalam kasus ini. Bukti-bukti yang dikumpulkan dari berbagai sumber diklaim cukup kuat untuk dilimpahkan ke tahap persidangan dalam waktu dekat.',
        'Kondisi cuaca ekstrem yang melanda beberapa hari terakhir membuat sejumlah aktivitas warga terhenti sementara. Badan Meteorologi, Klimatologi, dan Geofisika (BMKG) mengimbau masyarakat untuk tetap waspada dan menghindari daerah-daerah yang rawan terdampak.',
        'Penandatanganan perjanjian kerja sama antara kedua belah pihak dilakukan dalam suasana yang hangat dan penuh optimisme. Kemitraan strategis ini diharapkan mampu membuka peluang baru yang menguntungkan bagi kedua belah pihak dalam jangka panjang.',
    ];

    public function definition(): array
    {
        $title = static::$titles[array_rand(static::$titles)];

        // Ambil 4 paragraf acak
        $shuffled = static::$paragraphs;
        shuffle($shuffled);
        $content = collect(array_slice($shuffled, 0, 4))
            ->map(fn($p) => "<p>$p</p>")
            ->implode("\n");

        return [
            'region_id'   => Region::inRandomOrder()->first()->id ?? Region::factory(),
            'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory(),
            'title'       => $title,
            'slug'        => Str::slug($title) . '-' . fake()->unique()->numerify('###'),
            'content'     => $content,
            'image'       => null,
            'is_published' => true,
            'views'       => fake()->numberBetween(50, 10000),
            'created_at'  => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
