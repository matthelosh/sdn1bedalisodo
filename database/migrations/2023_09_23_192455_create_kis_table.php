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
        Schema::create('kis', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20);
            $table->string('tingkat', 2);
            $table->string('mapel_id', 30);
            $table->text('teks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kis');
    }
};
