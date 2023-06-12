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
        Schema::create('ingredients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ingredients_name');
            $table->unsignedInteger('recipe_id')->nullable();
            $table->foreign('recipe_id')->references('recipe_id')->on('recipe');
            $table->double('amount');
            $table->string('measure_name');
            $table->double('cups');
            $table->string('comments')->nullable();
            $table->unsignedInteger('archived')->default(0);
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
