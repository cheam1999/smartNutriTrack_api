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
        Schema::create('food_intakes', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('meal', ['BREAKFAST', 'LUNCH', 'SNACKS', 'DINNER','SUPPER']);
            $table->double('intake_serving_size') ->nullable();
            $table->unsignedInteger('users_id');
            $table->foreign('users_id')->references('id')->on('users');
            $table->unsignedInteger('food_id');
            $table->foreign('food_id')->references('id')->on('food');
            $table->dateTime('intake_date', $precision = 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_intakes');
    }
};
