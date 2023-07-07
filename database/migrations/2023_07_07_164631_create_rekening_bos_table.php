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
        Schema::create('rekening_bos', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 100);
            $table->text('rincian_obyek');
            $table->boolean('ppn');
            $table->boolean('pph21');
            $table->boolean('pph23');
            $table->boolean('pph4');
            $table->boolean('sspd');
            $table->string('blok_belanja', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekening_bos');
    }
};
