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
        Schema::create('peraturans', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 100);
            $table->string('no', 5);
            $table->string('tahun', 5);
            $table->text('tentang');
            $table->enum('jenis', ['Undang-undang', 'Peraturan Pemerintah', 'Peraturan Presiden', 'Permendikbud', 'Kepmendikbud', 'KepBskap', 'EdMen', 'lainnya']);
            $table->string('keterangan', 191)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peraturans');
    }
};
