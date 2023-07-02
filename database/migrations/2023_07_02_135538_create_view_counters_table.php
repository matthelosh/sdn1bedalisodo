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
        Schema::create('view_counters', function (Blueprint $table) {
            $table->id();
            $table->text('route');
            $table->integer('post_id')->nullable();
            $table->string('user_agent', 100)->nullable();
            $table->string('location', 100)->nullable();
            $table->string('ip_address', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('view_counters');
    }
};
