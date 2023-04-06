<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FoodIntakeController;
use App\Http\Controllers\DailyIntakeController;
use App\Http\Controllers\WeeklyIntakeController;

//Public Routes
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::get('/get_barcode_products/{code}', [FoodController::class, 'show']);
Route::get('/get_all', [FoodController::class, 'index']);



Route::group(['middleware' => ['auth:sanctum']], function(){
    //auth
    Route::post('/me', [UserController::class, 'me']);

    // add food intake
    Route::post('/save_meal', [FoodIntakeController::class, 'save_meal']);
    // retrieve all food intake
    Route::get('/get_current_meals', [FoodIntakeController::class, 'getCurrentMeals']);

    // Daily Intake Controller
    Route::get('/get_daily_summary', [DailyIntakeController::class, 'getDailySummary']);

    // Weekly Intake Controller
    Route::get('/get_weekly_summary', [WeeklyIntakeController::class, 'getWeeklySummary']);
    Route::post('/get_diabetes/{carb}', [WeeklyIntakeController::class, 'getDiabetes']);
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
