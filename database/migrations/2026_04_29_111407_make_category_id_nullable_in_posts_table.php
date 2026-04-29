<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Jadikan category_id nullable supaya auto-save bisa berjalan
            // meskipun editor belum memilih kategori
            $table->unsignedBigInteger('category_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable(false)->change();
        });
    }
};
