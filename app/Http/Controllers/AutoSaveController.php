<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AutoSaveController extends Controller
{
    /**
     * Auto-save draft berita.
     * - Jika ada 'id', update berita yang sudah ada.
     * - Jika tidak ada 'id', buat record draft baru dan kembalikan ID-nya.
     */
    public function save(Request $request, $id = null)
    {
        // Pastikan user sudah login
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $data = $request->only(['title', 'content', 'excerpt', 'category_id', 'slug']);

        // Bersihkan data kosong (jangan overwrite dengan null)
        $data = array_filter($data, fn($val) => !is_null($val) && $val !== '');

        if ($id) {
            // UPDATE: berita sudah ada di database
            $post = Post::find($id);

            if (!$post) {
                return response()->json(['error' => 'Post not found'], 404);
            }

            // Jangan overwrite status publish, hanya update konten
            $post->fill($data)->save();

            return response()->json([
                'status'  => 'updated',
                'id'      => $post->id,
                'savedAt' => now()->format('H:i:s'),
            ]);
        } else {
            // CREATE: buat draft baru di database
            if (empty($data['title'])) {
                return response()->json(['status' => 'skipped', 'reason' => 'Judul masih kosong'], 200);
            }

            // Generate slug unik
            $baseSlug = Str::slug($data['title']);
            $slug = $baseSlug;
            $counter = 1;
            while (Post::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }

            $post = Post::create([
                'title'        => $data['title'],
                'slug'         => $slug,
                'content'      => $data['content'] ?? '',
                'excerpt'      => $data['excerpt'] ?? null,
                'category_id'  => $data['category_id'] ?? null,
                'is_published' => false,
                'created_by'   => auth()->id(),
            ]);

            return response()->json([
                'status'  => 'created',
                'id'      => $post->id,
                'savedAt' => now()->format('H:i:s'),
            ]);
        }
    }
}
