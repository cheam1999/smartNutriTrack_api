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
        Schema::create('weekly_intakes', function (Blueprint $table) {
            $table->increments('id');
            $table->double('w_energy_kcal') ->nullable();
            $table->double('w_carbohydrates') ->nullable();
            $table->double('w_proteins') ->nullable();
            $table->double('w_sodium') ->nullable();
            $table->double('w_calcium') ->nullable();
            $table->dateTime('w_sun_date', $precision = 0);
            
            $table->unsignedInteger('users_id');
            $table->foreign('users_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_intakes');
    }
};
