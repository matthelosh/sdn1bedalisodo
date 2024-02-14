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
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat', 100);
            $table->string('kategori', 50);
            $table->string('klasifikasi_id', 10);
            $table->string('penerima', 191);
            $table->text('perihal');
            $table->date('tanggal');
            $table->string('tembusan', 191)->nullable();
            $table->string('status', 191);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};
