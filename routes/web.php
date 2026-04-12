<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/news/{slug}', [HomeController::class, 'show'])->name('news.show');

// ============================================================
// DEPLOYMENT BRIDGE - Khusus Setup Shared Hosting (Tanpa SSH)
// Gunakan hanya SATU KALI setelah upload ke server.
// Hapus atau disable setelah setup selesai!
// Akses: https://domainanda.com/setup-server?token=DARNUS2025
// ============================================================
Route::get('/setup-server', function () {
    // Validasi token pengaman
    $token = request('token');
    $validToken = env('DEPLOY_TOKEN', 'DARNUS2025');

    if ($token !== $validToken) {
        abort(403, 'Unauthorized. Token tidak valid.');
    }

    $output = [];

    // 1. Buat symlink storage
    try {
        Artisan::call('storage:link');
        $output[] = '✅ storage:link — Berhasil';
    } catch (\Exception $e) {
        $output[] = '⚠️ storage:link — ' . $e->getMessage();
    }

    // 2. Jalankan migrasi database
    try {
        Artisan::call('migrate', ['--force' => true]);
        $output[] = '✅ migrate —  Berhasil';
    } catch (\Exception $e) {
        $output[] = '❌ migrate — ' . $e->getMessage();
    }

    // 3. Jalankan seeder (isi akun admin)
    try {
        Artisan::call('db:seed', ['--force' => true]);
        $output[] = '✅ db:seed — Berhasil (Akun admin dibuat)';
    } catch (\Exception $e) {
        $output[] = '❌ db:seed — ' . $e->getMessage();
    }

    // 4. Bersihkan cache
    try {
        Artisan::call('optimize:clear');
        $output[] = '✅ optimize:clear — Cache dibersihkan';
    } catch (\Exception $e) {
        $output[] = '⚠️ optimize:clear — ' . $e->getMessage();
    }

    return response(
        '<h1 style="font-family:monospace">🚀 DarnusNews Deployment Bridge</h1>' .
        '<pre style="font-family:monospace;font-size:16px;line-height:2">' .
        implode("\n", $output) .
        '</pre>' .
        '<p style="font-family:monospace;color:green"><b>✅ Setup selesai! Hapus atau nonaktifkan route ini sekarang.</b></p>'
    );
})->name('setup.server');
