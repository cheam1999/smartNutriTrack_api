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
        Schema::create('daily_intakes', function (Blueprint $table) {
            $table->increments('id');
            $table->double('d_energy_kcal') ->nullable();
            $table->double('d_carbohydrates') ->nullable();
            $table->double('d_proteins') ->nullable();
            $table->double('d_sodium') ->nullable();
            $table->double('d_calcium') ->nullable();
            $table->dateTime('d_intake_date', $precision = 0);
            
             
            $table->foreign('users_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_intakes');
    }
};
