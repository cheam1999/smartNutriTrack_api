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
        Schema::create('recipe', function (Blueprint $table) {
            $table->increments('recipe_id');
            $table->string('recipe_name', 255);
            $table->string('recipe_image',255)->nullable();
            // $table->enum('recipe_level', ['Beginner', 'Intermediate', 'Masterchef'])->default('Intermediate');
            $table->longText('recipe_ingredients')->nullable();
            $table->longText('recipe_instructions')->nullable();
            $table->string('recipe_source', 255)->nullable();
            $table->unsignedInteger('recipe_meal')->nullable();
            // $table->string('recipe_video', 255)->nullable();
            $table->timestamps();
        });
    }



    public function down(): void
    {
        Schema::dropIfExists('recipe');
    }
};
