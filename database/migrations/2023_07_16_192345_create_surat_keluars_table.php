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
        Schema::create('surat_keluars', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat', 100)->unique();
            $table->string('klasifikasi_id', 10);
            $table->date('tanggal_surat');
            $table->string('sifat', 50); //[Sangat Rahasia, Rahasia, Terbatas, Biasa]
            $table->string('perihal', 100);
            $table->string('tipe', 50); //[Keputusan, Perintah, Perjalanan Dinas, Undangan, Pemberitahuan]
            $table->enum('lingkup', ['Internal','Eksternal']);
            $table->string('pengirim', 100);
            $table->string('penerima', 100);
            $table->string('alamat', 100);
            $table->text('ringkasan');
            $table->string('file_surat', 191)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_keluars');
    }
};
