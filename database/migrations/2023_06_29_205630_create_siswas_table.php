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
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->string('nisn', 12)->unique();
            $table->string('nis', 10)->nullable();
            $table->string('nama', 60);
            $table->enum('jk', ['Laki-laki', 'Perempuan']);
            $table->string('tempat_lahir', 40)->default('Malang');
            $table->date('tanggal_lahir');
            $table->string('alamat', 60)->default('Sengon');
            $table->enum('agama',['Islam','Kristen','Katolik','Hindu','Budha','Konghuchu'])->default('Islam');
            $table->string('rt', 3)->nullable();
            $table->string('rw', 3)->nullable();
            $table->string('desa', 60)->default('Dalisodo');
            $table->string('kecamatan', 60)->default('Wagir');
            $table->string('kode_pos', 5);
            $table->string('kabupaten', 60)->default('Malang');
            $table->string('hp', 15)->nullable();
            $table->string('email', 60)->unique();
            $table->string('nik', 16)->nullable();
            $table->integer('ortu_id')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas', 'rombel_siswa');
    }
};
