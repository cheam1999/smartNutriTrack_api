<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarcodeProductsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barcode_products', function (Blueprint $table) {
            $table->string('food_code') ->primary();
            $table->string('food_name');
            $table->unsignedInteger('food_quantity');
            $table->unsignedInteger('food_serving_size');
            $table->unsignedInteger('energy_kcal_100g');
            $table->unsignedInteger('carbohydrates_100g');
            $table->unsignedInteger('proteins_100g');
            $table->unsignedInteger('fat_100g');
            $table->unsignedInteger('sodium_100g');
            $table->unsignedInteger('vitamin_D_100g');
            $table->unsignedInteger('calcium_100g');
            $table->unsignedInteger('iron_100g');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barcode_products');
    }
};
