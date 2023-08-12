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
        Schema::table('mapels', function (Blueprint $table) {
            $table->enum('kelompok', ['Kelompok A', 'Kelompok B'])->default('Kelompok A');
            $table->enum('kategori', ['Wajib','Mulok', 'Pilihan'])->default('Wajib');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mapels', function (Blueprint $table) {
            //
        });
    }
};
