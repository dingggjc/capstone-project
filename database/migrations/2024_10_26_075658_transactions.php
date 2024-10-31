<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cashier_id')->index();
            $table->string('invoice')->unique();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('vehicle_type')->nullable();
            $table->string('vehicle_plate')->nullable();
            $table->bigInteger('cash');
            $table->bigInteger('change');
            $table->bigInteger('grand_total');
            $table->string('status')->default('Pending');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('cashier_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
