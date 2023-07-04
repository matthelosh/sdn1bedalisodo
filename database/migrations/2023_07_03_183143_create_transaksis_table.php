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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe', ['debet','kredit']);
            $table->enum('jenis', ['tunai','nontunai'])->default('tunai');
            $table->date('tanggal');
            $table->text('uraian');
            $table->string('kode_kegiatan', 20);
            $table->string('kode_rekening', 60);
            $table->string('no_bukti', 20);
            $table->string('merchant', 191);
            $table->float('nilai', 12,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
