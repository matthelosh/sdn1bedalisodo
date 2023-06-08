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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('author_id', 60);
            $table->string('category_id', 30);
            $table->string('slug', 191);
            $table->string('title', 191);
            $table->string('featured_image', 100)->nullable();
            $table->text('content');
            $table->enum('status', ['draft','published','edited'])->default('draft');
            $table->boolean('starred')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
