<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('isbn')->unique();
            $table->string('title');
            $table->string('author')->nullable();
            $table->string('publisher')->nullable();
            $table->unsignedSmallInteger('publish_year')->nullable();
            $table->text('synopsis')->nullable();
            $table->string('category')->nullable();
            $table->string('cover_url')->nullable();      // dari Google Books API
            $table->string('cover_path')->nullable();     // override upload lokal
            $table->string('rack_location')->nullable();
            $table->unsignedInteger('total_stock')->default(0);
            $table->unsignedInteger('available_stock')->default(0);
            $table->timestamps();

            $table->index('title');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};