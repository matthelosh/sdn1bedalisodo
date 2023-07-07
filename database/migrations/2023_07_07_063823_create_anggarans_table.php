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
        Schema::create('anggarans', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_anggaran',4);
            $table->string('sumber_dana', 60);
            $table->string('tahap', 30);
            $table->string('bulan_anggaran', 30);
            $table->string('kode_rekening', 100);
            $table->string('kode_kegiatan', 100);
            $table->text('uraian');
            $table->integer('volume');
            $table->string('stauan', 60);
            $table->float('tarif', 12,2);
            $table->string('keterangan', 191)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggarans');
    }
};
