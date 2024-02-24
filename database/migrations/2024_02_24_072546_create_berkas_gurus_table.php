<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('berkas_gurus', function (Blueprint $table) {
            $table->id();
            $table->string('tipe', 60);
            $table->string('guru_id', 30);
            $table->string('label', 191);
            $table->string('deskripsi', 191)->nullable();
            $table->string('keterangan', 191)->nullable();
            $table->text('url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berkas_gurus');
    }
};
