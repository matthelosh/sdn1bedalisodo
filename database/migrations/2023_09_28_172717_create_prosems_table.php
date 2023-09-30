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
        Schema::create('prosems', function (Blueprint $table) {
            $table->id();
            $table->string('guru_id', 30);
            $table->string('mapel_id', 10);
            $table->string('rombel_id', 30);
            $table->integer('prota_id');
            $table->date('tanggal');
            $table->string('semester', 5);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prosems');
    }
};
