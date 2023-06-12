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
        Schema::create('food', function (Blueprint $table) {
            $table->increments('id');
            $table->string('food_code') ->nullable();
            $table->string('food_name');
            $table->unsignedInteger('food_quantity');
            $table->unsignedInteger('food_serving_size') ->nullable();
            $table->unsignedInteger('energy_kcal_100g') ->nullable();
            $table->double('carbohydrates_100g') ->nullable();
            $table->double('proteins_100g') ->nullable();
            $table->double('sodium_100g') ->nullable();
            $table->double('calcium_100g') ->nullable();
            $table->unsignedInteger('food_verified');
            $table->unsignedInteger('food_created_by');
            $table->unsignedInteger('food_verified_by')->nullable();
            $table->unsignedInteger('archived')->default(0);
            $table->foreign('food_created_by')->references('id')->on('users');
            $table->foreign('food_verified_by')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food');
    }
};
