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
        Schema::create('cps', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10)->unique();
            $table->string('fase', 1);
            $table->string('tingkat', 1);
            $table->string('elemen', 30);
            $table->text('teks');
            $table->string('mapel_id', 6);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cps');
    }
};
