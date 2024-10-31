<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('product_inventory_id')->nullable();
            $table->integer('qty');
            $table->decimal('product_price', 10, 2)->nullable();
            $table->unsignedBigInteger('package_id')->nullable();
            $table->decimal('package_price', 10, 2)->nullable();

            $table->timestamps();
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->foreign('product_inventory_id')->references('product_inventory_id')->on('product_inventory')->onDelete('cascade');
            $table->foreign('package_id')->references('package_id')->on('t_package')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction_details');
    }
};
