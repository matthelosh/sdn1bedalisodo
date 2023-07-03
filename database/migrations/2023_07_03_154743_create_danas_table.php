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
        Schema::create('danas', function (Blueprint $table) {
            $table->id();
            $table->string('tahap', 30);
            $table->date('tanggal');
            $table->text('uraian');
            $table->string('tahun_anggaran', 30);
            $table->string('sumber', 60);
            $table->float('masuk', 12,2);
            $table->float('saldo',12,2);
            $table->string('keterangan', 191);
            $table->string('user_id', 30);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('danas');
    }
};
