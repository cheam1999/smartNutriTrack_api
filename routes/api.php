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
use App\Http\Controllers\IngredientsController;

//Public Routes
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/nutritionistLogin', [UserController::class, 'nutritionistLogin']);

// Food Controller
Route::get('/get_barcode_products/{code}', [FoodController::class, 'get_barcode_products']);
Route::get('/get_all_food', [FoodController::class, 'index']);
Route::get('/get_unverified_food', [FoodController::class, 'getUnverifiedFood']);
Route::get('/get_food_by_id/{id}', [FoodController::class, 'get_food_by_id']);
Route::get('/generate_ingredients{url}', [FoodController::class, 'generateIngredients']);

// Recipe Controller

Route::get('/get_recipe', [RecipeController::class, 'index']);
Route::get('/get_recipe_by_id/{id}', [RecipeController::class, 'get_recipe_by_id']);
Route::get('/count_recipe', [RecipeController::class, 'count_recipe']);
Route::get('/get_breakfast_recipe', [RecipeController::class, 'get_breakfast_recipe']);
Route::get('/get_lunch_recipe', [RecipeController::class, 'get_lunch_recipe']);

//Ingredient 
Route::post('/generate_grocery', [IngredientsController::class, 'generate_daily_grocery']);
Route::get('/get_ingredients', [IngredientsController::class, 'index']);
Route::get('/get_ing_by_id/{id}', [IngredientsController::class, 'get_ing_by_id']);


Route::group(['middleware' => ['auth:sanctum']], function(){
    //auth
    Route::post('/me', [UserController::class, 'me']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('/updateProfileDetails', [UserController::class, 'updateProfileDetails']);

    //Recipe Controller
    Route::post('/create_recipe', [RecipeController::class, 'create']);
    Route::post('/update_recipe', [RecipeController::class, 'update_recipe']);
    Route::post('/archived_recipe/{id}',[RecipeController::class, 'archivedRecipe']);

    // Ingredients
    Route::post('/create_ingredients', [IngredientsController::class, 'create']);
    Route::post('/update_ingredients', [IngredientsController::class, 'update_ing']);
    Route::post('/archived_ingredients/{id}',[IngredientsController::class, 'archivedIng']);
    // Route::post('/process_json', [IngredientsController::class, 'processJsonText']);
    // Food Controller
    Route::post('/create_food',[FoodController::class, 'createFood']);
    Route::post('/verify_food/{id}',[FoodController::class, 'verifiedFood']);
    Route::post('/archived_food/{id}',[FoodController::class, 'archivedFood']);
    Route::post('/update_food',[FoodController::class, 'updateFood']);

    // add food intake
    Route::post('/save_meal', [FoodIntakeController::class, 'save_meal']);
    // retrieve all food intake
    Route::get('/get_current_meals', [FoodIntakeController::class, 'getCurrentMeals']);
    Route::post('/delete_meal', [FoodIntakeController::class, 'delete_meal']);

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
