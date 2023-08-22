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
        Schema::create('ortus', function (Blueprint $table) {
            $table->id();
            $table->string('no_kk', 30)->nullable();
            $table->string('nama_ayah', 60)->nullable();
            $table->string('tahun_lahir_ayah', 4)->nullable();
            $table->string('pekerjaan_ayah', 60)->nullable();
            $table->string('penghasilan_ayah', 191)->nullable();
            $table->string('nik_ayah', 31)->nullable();
            $table->string('nama_ibu', 60)->nullable();
            $table->string('tahun_lahir_ibu', 4)->nullable();
            $table->string('pekerjaan_ibu', 60)->nullable();
            $table->string('penghasilan_ibu', 191)->nullable();
            $table->string('nik_ibu', 31)->nullable();
            $table->string('nama_wali', 60)->nullable();
            $table->string('tahun_lahir_wali', 4)->nullable();
            $table->string('pekerjaan_wali', 60)->nullable();
            $table->string('penghasilan_wali', 191)->nullable();
            $table->string('nik_wali', 31)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ortus');
    }
};
