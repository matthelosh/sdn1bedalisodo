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
        Schema::create('bkus', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('kode_kegiatan', 20);
            $table->string('kode_rekening', 60);
            $table->string('no_bukti', 20);
            $table->text("uraian");
            $table->float("debet", 12,2);
            $table->float("kredit", 12,2);
            $table->float("saldo", 12,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bkus');
    }
};
