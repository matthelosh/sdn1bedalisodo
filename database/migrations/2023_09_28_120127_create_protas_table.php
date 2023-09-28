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
        Schema::create('protas', function (Blueprint $table) {
            $table->id();
            $table->string('alur', 5);
            $table->string('guru_id', 30);
            $table->string('rombel_id', 30);
            $table->string('mapel_id', 30);
            $table->integer('atp_id');
            $table->string('tingkat', 3);
            $table->integer('materi_id');
            $table->string('jp', 3);
            $table->string('semester', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('protas');
    }
};
