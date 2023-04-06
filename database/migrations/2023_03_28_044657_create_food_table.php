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
            $table->double('food_quantity');
            $table->double('food_serving_size') ->nullable();
            $table->double('energy_kcal_100g') ->nullable();
            $table->double('carbohydrates_100g') ->nullable();
            $table->double('proteins_100g') ->nullable();
            $table->double('sodium_100g') ->nullable();
            $table->double('calcium_100g') ->nullable();
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
