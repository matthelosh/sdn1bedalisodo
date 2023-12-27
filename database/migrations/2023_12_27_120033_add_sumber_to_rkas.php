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
        Schema::table('rkas', function (Blueprint $table) {
            $table->enum('sumber', ['bosreg', 'bosda', 'bosdakin'])->default('bosreg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rkas', function (Blueprint $table) {
            //
        });
    }
};
