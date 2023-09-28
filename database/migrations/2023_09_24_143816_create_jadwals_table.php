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
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->string('guru_id', 30);
            $table->string('tapel', 8);
            $table->string('mapel_id', 20);
            $table->string('rombel_id', 30);
            $table->string('jamke', 3);
            $table->string('jml_jam', 3)->default('4');
            $table->enum('hari', ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};
