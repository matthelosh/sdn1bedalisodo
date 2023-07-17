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
        Schema::create('sumber_danas', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 30)->unique();
            $table->string('tahun_anggaran', 4);
            $table->string('sumber', 60);
            $table->string('label', 100);
            $table->string('tahap', 20);
            $table->float('jumlah', 12,2);
            $table->float('saldo', 12,2);
            $table->string('keterangan', 191)->nullable();
            $table->enum('status', ['running', 'finished']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sumber_danas');
    }
};
