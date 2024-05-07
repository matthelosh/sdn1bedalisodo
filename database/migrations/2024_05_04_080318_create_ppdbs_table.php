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
        Schema::create('ppdbs', function (Blueprint $table) {
                $table->id();
                $table->string('kode', 16)->unique();
                $table->string('tapel', 6);
                $table->string('nisn', 35)->nullable();
                $table->string('nik', 20)->nullable();
                $table->string('nama', 100);
                $table->enum('jk', ['Laki-laki','Perempuan']);
                $table->string('tempat_lahir', 60)->default('Malang');
                $table->date('tanggal_lahir');
                $table->string('alamat', 60);
                $table->string('rt', 3)->nullable();
                $table->string('rw', 3)->nullable();
                $table->string('desa', 60);
                $table->string('kecamatan', 100)->default('Wagir');
                $table->string('kabupaten', 100)->default('Malang');
                $table->string('kode_pos', 5)->default('65158');
                $table->string('hp', 16)->nullable();
                $table->string('email', 100)->nullable();
                $table->string('asal_tk', 100)->nullable();
                $table->string('nama_ayah', 60);
                $table->string('nik_ayah', 16)->nullable();
                $table->string('nama_ibu', 60);
                $table->string('nik_ibu', 16)->nullable();
                $table->string('nama_wali', 100)->nullable();
                $table->string('nik_wali', 16)->nullable();
                $table->string('hubungan_wali', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppdbs');
    }
};
