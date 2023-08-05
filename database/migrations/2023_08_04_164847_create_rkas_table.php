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
        Schema::create('rkas', function (Blueprint $table) {
            $table->id();
            $table->string('anggaran_id', 20);
            $table->string('bulan', 12);
            $table->string('tahun', 4);
            $table->enum('status', ['antri','progres','selesai','gagal'])->default('antri');
            $table->string('kode_rekening', 40);
            $table->string('kode_kegiatan', 40);
            $table->text('uraian');
            $table->integer('volume');
            $table->string('satuan', 60);
            $table->float('tarif', 12, 2);
            $table->float('jumlah', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rkas');
    }
};
