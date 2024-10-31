<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cashier_id')->index();
            $table->unsignedBigInteger('product_inventory_id')->nullable()->index();
            $table->unsignedBigInteger('package_id')->nullable()->index();
            // $table->string('customer_name')->nullable();
            // $table->string('customer_phone')->nullable();
            // $table->string('vehicle_type')->nullable();
            // $table->string('vehicle_plate')->nullable();
            $table->integer('qty');
            $table->bigInteger('price');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('cashier_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_inventory_id')->references('product_inventory_id')->on('product_inventory')->onDelete('set null');
            $table->foreign('package_id')->references('package_id')->on('t_package')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('carts');
    }
};