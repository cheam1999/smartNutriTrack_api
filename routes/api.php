<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FoodIntakeController;
use App\Http\Controllers\DailyIntakeController;
use App\Http\Controllers\WeeklyIntakeController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\UserRecipeController;

//Public Routes
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

// Food Controller
Route::get('/get_barcode_products/{code}', [FoodController::class, 'get_barcode_products']);
Route::get('/get_all_food', [FoodController::class, 'index']);

// Recipe Controller
Route::post('/create_recipe', [RecipeController::class, 'create']);
Route::get('/get_recipe', [RecipeController::class, 'index']);
Route::get('/get_recipe_by_id/{id}', [RecipeController::class, 'get_recipe_by_id']);
Route::get('/count_recipe', [RecipeController::class, 'count_recipe']);
Route::get('/get_breakfast_recipe', [RecipeController::class, 'get_breakfast_recipe']);
Route::get('/get_lunch_recipe', [RecipeController::class, 'get_lunch_recipe']);

Route::group(['middleware' => ['auth:sanctum']], function(){
    //auth
    Route::post('/me', [UserController::class, 'me']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('/updateProfileDetails', [UserController::class, 'updateProfileDetails']);

    // add food intake
    Route::post('/save_meal', [FoodIntakeController::class, 'save_meal']);
    // retrieve all food intake
    Route::get('/get_current_meals', [FoodIntakeController::class, 'getCurrentMeals']);

    // Daily Intake Controller
    Route::get('/get_daily_summary', [DailyIntakeController::class, 'getDailySummary']);

    // Weekly Intake Controller
    Route::get('/get_weekly_summary', [WeeklyIntakeController::class, 'getWeeklySummary']);
    Route::post('/get_diabetes/{carb}', [WeeklyIntakeController::class, 'getDiabetes']);

    //User Recipe
    Route::get('/get_user_recipe', [UserRecipeController::class, 'index']);
    Route::post('create_user_recipe',[UserRecipeController::class, 'create']);
    Route::get('/get_by_userID', [UserRecipeController::class, 'getByUserID']);
    Route::post('/updateUr/{meal}', [UserRecipeController::class, 'updateUr']);
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
