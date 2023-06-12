<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;
use DB;

class FoodController extends Controller
{
    public function index()
    {
        $food = DB::table('food')
        ->where('archived', '=', 0)
        ->get();

        return $food;
    }

    public function get_barcode_products(string $code)
    {
        $scanned_food = DB::table('food')
                    ->where('food_code', '=', $code)
                    ->get();

        return $scanned_food;
    }

    public function createFood(Request $request){
        $user = auth()->user();

        $food = $request->validate([
            'food_name' => 'required|string',
            'food_quantity' => 'required',
            'food_serving_size' => 'required',
            'energy_kcal_100g' => 'required',
            'carbohydrates_100g' => 'required',
            'proteins_100g' => 'required',
            'sodium_100g' => 'required',
            'calcium_100g'  => 'required',
            
        ]);

        $nutritionist = 0;

        if($user->nutritionist == 1)
            $nutritionist = 1;
            


        $newFood = Food::create([
            'food_code' => "",
            'food_name'=> $food['food_name'],
            'food_quantity'=> $food['food_quantity'],
            'food_serving_size' => $food['food_serving_size'],
            'energy_kcal_100g' => $food['energy_kcal_100g'],
            'carbohydrates_100g'=> $food['carbohydrates_100g'],
            'proteins_100g' => $food['proteins_100g'],
            'sodium_100g' => $food['sodium_100g'],
            'calcium_100g' => $food['calcium_100g'],
            'food_verified' => $nutritionist,
            'food_created_by' => $user->id,    
        ])->save();

        return $newFood;
    }

    public function getUnverifiedFood(){
        $food = DB::table('food')
                    ->where('food_verified', '=', 0)
                    ->where('archived', '=', 0)
                    ->get();

        return $food;
    }

    public function verifiedFood(string $id){
        $user = auth()->user();

        // print($user->id);

        $updatedFood = Food::where('food.id', '=', $id)
            ->update(array('food_verified' => "1", 'food_verified_by' => $user->id));

        return $updatedFood;
    }

    public function get_food_by_id(string $id)
    {
        $retrieved_food = DB::table('food')
                    ->where('id', '=', $id)
                    ->get();

        return $retrieved_food;
    }

    public function archivedFood(string $id){

        $user = auth()->user();

        $archived_food = DB::table('food')
        ->where('id', '=', $id)
        ->update(array('archived' => "1", 'food_verified_by' => $user->id));

        return $archived_food;
    }

    public function updateFood(Request $request){
        $user = auth()->user();

        $food = $request->validate([
            'id' => 'required',
            'food_code' => '',
            'food_name' => 'required|string',
            'food_quantity' => 'required',
            'food_serving_size' => 'required',
            'energy_kcal_100g' => 'required',
            'carbohydrates_100g' => 'required',
            'proteins_100g' => 'required',
            'sodium_100g' => 'required',
            'calcium_100g'  => 'required',
            
        ]);

        $nutritionist = 0;

        if($user->nutritionist == 1)
            $nutritionist = 1;

        if($food['food_code'] == null || $food['food_code'] == "")
            $code = "";
        else
            $code = $food['food_code'];
            


        $updateFood = Food::where('id', $food['id'])->update([
            'food_code' => $code,
            'food_name'=> $food['food_name'],
            'food_quantity'=> $food['food_quantity'],
            'food_serving_size' => $food['food_serving_size'],
            'energy_kcal_100g' => $food['energy_kcal_100g'],
            'carbohydrates_100g'=> $food['carbohydrates_100g'],
            'proteins_100g' => $food['proteins_100g'],
            'sodium_100g' => $food['sodium_100g'],
            'calcium_100g' => $food['calcium_100g'],
            'food_verified' => $nutritionist,
            'food_created_by' => $user->id,    
        ]);

        return $updateFood;
    }

    public function generateIngredients(String $url){
        $command = 'ingredients ' +  $url;
        $output = `$command`;

        print($output);
    }
}
