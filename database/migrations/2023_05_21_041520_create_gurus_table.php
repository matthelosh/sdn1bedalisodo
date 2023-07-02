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
        Schema::create('gurus', function (Blueprint $table) {
            $table->id();
            $table->string('nip',30);
            $table->string('nama', 60);
            $table->enum('jk', ['Laki-laki','Perempuan']);
            $table->string('tempat_lahir', 35);
            $table->date('tanggal_lahir');
            $table->enum('agama', ['Islam','Kristen','Katolik','Hindu','Budha','Konghuchu'])->default('Islam');
            $table->string('alamat',191)->default('Malang');
            $table->string('hp',15)->default('62000000');
            $table->string('foto', 191)->nullable();
            $table->enum('role', ['admin','ks','gpai','gor','geks','gkel','pjg'])->default('gkel');
            $table->string('facebook', 60)->nullable();
            $table->string('youtube', 60)->nullable();
            $table->string('instagram', 60)->nullable();
            $table->string('nickname', 60)->nullable();
            $table->enum('status', ['active','inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gurus');
    }
};
