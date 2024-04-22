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
        Schema::create('book', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->double('price')->default(0);
            $table->integer('quantity')->default(0);
            $table->string('author')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('category');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book');
    }
};
