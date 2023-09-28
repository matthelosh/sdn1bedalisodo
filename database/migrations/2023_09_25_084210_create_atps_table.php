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
        Schema::create('atps', function (Blueprint $table) {
            $table->id();
            $table->string('alur', 5);
            $table->string('mapel_id', 20);
            $table->string('tingkat', 3);
            $table->text('teks');
            $table->enum('fase', ['A','B','C']);
            $table->string('elemen', 50);
            $table->string('jp', 3);
            $table->text('keywords');
            $table->text('ppp');
            $table->text('glosarium');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atps');
    }
};
