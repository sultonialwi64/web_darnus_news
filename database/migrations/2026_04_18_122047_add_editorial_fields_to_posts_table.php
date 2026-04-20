<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // author_id + FK-nya ke journalists sudah ada
            // Tambah yang belum ada saja
            $table->foreignId('editor_id')->nullable()->constrained('journalists')->nullOnDelete();
            $table->string('source')->nullable();
            $table->text('excerpt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['editor_id']);
            $table->dropColumn(['editor_id', 'source', 'excerpt']);
        });
    }
};
