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
            $table->string('kode',30)->unique();
            $table->string('sumber_dana', 60)->default('BOS REGULER');
            $table->string('tahap', 30);
            $table->text('uraian');
            $table->float('nilai', 12,2);
            $table->string('keterangan', 191)->nullable();
            $table->enum('status', ['aktif','nonaktif'])->default('nonaktif');
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
