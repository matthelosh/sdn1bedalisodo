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
        Schema::create('prestasis', function (Blueprint $table) {
            $table->id();
            $table->string('siswa_id', 15);
            $table->string('kegiatan', 191);
            $table->string('bidang', 60);
            $table->enum('tingkat',['Sekolah','Gugus','Kecamatan','Kabupaten','Provinsi','Nasional','Internasional']);
            $table->string('peringkat', 60);
            $table->string('foto', 191);
            $table->text('deskripsi');
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestasis');
    }
};
