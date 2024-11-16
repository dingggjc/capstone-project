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
        Schema::create('product_specials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('specials_id');
            $table->unsignedBigInteger('product_inventory_id');
            $table->integer('quantity');
            $table->timestamps();
            $table->foreign('product_inventory_id')->references('product_inventory_id')->on('product_inventory')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_specials');
    }
};
