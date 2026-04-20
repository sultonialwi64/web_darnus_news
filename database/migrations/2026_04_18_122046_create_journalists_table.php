<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journalists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position')->nullable(); // Contoh: Reporter, Redaktur, Fotografer
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journalists');
    }
};
