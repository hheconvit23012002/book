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
        Schema::create('history_add_product', function (Blueprint $table) {
            $table->id();
            $table->integer('number')->default(0);
            $table->unsignedBigInteger('book_id');
            $table->foreign('book_id')->references('id')->on('book');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_add_product');
    }
};
