<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/news/{slug}', [HomeController::class, 'show'])->name('news.show');
Route::get('/tentang-kami', [HomeController::class, 'about'])->name('about');
Route::get('/susunan-redaksi', [HomeController::class, 'editorial'])->name('editorial');
Route::get('/tag/{slug}', [HomeController::class, 'tag'])->name('tag.show');

// ============================================================
// DEPLOYMENT BRIDGE - Khusus Setup Shared Hosting (Tanpa SSH)
// Akses: https://darnusnews.com/setup-server?token=DarnusSetup2025
// ============================================================
Route::get('/setup-server', function () {
    $token = request('token');
    $validToken = env('DEPLOY_TOKEN', 'DarnusSetup2025');

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
        $output[] = '✅ migrate — Berhasil';
    } catch (\Exception $e) {
        $output[] = '❌ migrate — ' . $e->getMessage();
    }

    // 3. Bersihkan cache
    try {
        Artisan::call('optimize:clear');
        $output[] = '✅ optimize:clear — Cache dibersihkan';
    } catch (\Exception $e) {
        $output[] = '⚠️ optimize:clear — ' . $e->getMessage();
    }

    // 4. Pindahkan foto dari folder private ke public (fix FILESYSTEM_DISK lama)
    try {
        $privatePath = storage_path('app/private/posts');
        $publicPath = storage_path('app/public/posts');

        if (!file_exists($publicPath)) {
            mkdir($publicPath, 0755, true);
        }

        $moved = 0;
        if (is_dir($privatePath)) {
            foreach (glob($privatePath . '/*') as $file) {
                $filename = basename($file);
                $dest = $publicPath . '/' . $filename;
                if (!file_exists($dest)) {
                    rename($file, $dest);
                    $moved++;
                }
            }
        }
        $output[] = "✅ Foto dipindah — {$moved} file berhasil dipindah dari private ke public";
    } catch (\Exception $e) {
        $output[] = '⚠️ Pindah foto — ' . $e->getMessage();
    }

    // 5. Download foto dummy & pasang ke semua berita yang tidak punya foto
    try {
        $dummyImg = 'posts/dummy_news.jpg';
        $dummyPath = storage_path('app/public/' . $dummyImg);

        if (!file_exists($dummyPath)) {
            if (!file_exists(dirname($dummyPath))) {
                mkdir(dirname($dummyPath), 0755, true);
            }
            $imgData = @file_get_contents('https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=1000&q=80');
            if ($imgData) {
                file_put_contents($dummyPath, $imgData);
                $output[] = '✅ Foto dummy — Berhasil diunduh dari Unsplash';
            } else {
                $output[] = '⚠️ Foto dummy — Gagal mengunduh, cek koneksi server';
            }
        } else {
            $output[] = '✅ Foto dummy — Sudah ada, skip download';
        }

        $updated = \App\Models\Post::whereNull('image')->update(['image' => $dummyImg]);
        $output[] = "✅ Foto berita — {$updated} berita dummy berhasil dipasang foto";
    } catch (\Exception $e) {
        $output[] = '❌ Foto dummy — ' . $e->getMessage();
    }

    return response(
        '<h1 style="font-family:monospace">🚀 DarnusNews Deployment Bridge</h1>' .
        '<pre style="font-family:monospace;font-size:16px;line-height:2">' .
        implode("\n", $output) .
        '</pre>' .
        '<p style="font-family:monospace;color:green"><b>✅ Setup selesai!</b></p>'
    );
})->name('setup.server');
