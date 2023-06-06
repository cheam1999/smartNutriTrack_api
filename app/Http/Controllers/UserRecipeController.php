<?php

namespace App\Http\Controllers;
use App\Models\User_recipe;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class UserRecipeController extends Controller
{
    public function index()
    {
        return User_recipe::withSelectUserRecipe()->get();
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        $validatedData = $request->validate([
            'ur_breakfast' => 'required',
            'ur_lunch' => 'required',
            'ur_snacks'=> 'required',
            'ur_dinner'=> 'required',
        ]);

        $success = User_recipe::create([
            'ur_breakfast' => $request['ur_breakfast'],
            'ur_lunch'=> $request['ur_lunch'],
            'ur_snacks'=> $request['ur_snacks'],
            'ur_dinner'=> $request['ur_dinner'],
            'users_id' => $user->id,
            'ur_date' => Carbon::now()->format('Y-m-d'),
            'created_at' => Carbon::now(),
            'update_at' => Carbon::now(),
        ])->save();

        

        return $success;
    }

    public function getByUserID(){
        $user = auth()->user();

        $result = DB::table('user_recipes')
        ->where('users_id', '=', $user->id)
        ->where('ur_date', '=', Carbon::now()->format('Y-m-d'))
        ->get();

        
        $check = json_decode($result);

        if(empty($check)){ //create new random combination of recipe
            $recipeClass = new RecipeController();
            $urClass = new UserRecipeController();
            $temp = $recipeClass->count_recipe();

            $breakfast = $urClass->randomMealSelector($temp[0]);
            $lunch = $urClass->randomMealSelector($temp[1]);
            $snacks = $urClass->randomMealSelector($temp[2]);
            $dinner = $urClass->randomMealSelector($temp[3]);


            $request = User_recipe::create([
                "ur_breakfast"=> $breakfast,
                "ur_lunch"=> $lunch,
                "ur_snacks"=> $snacks,
                "ur_dinner"=> $dinner,
                "users_id" => $user->id,
                'ur_date' => Carbon::now()->format('Y-m-d'),
                'created_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ]);

            if($request){
                $result = DB::table('user_recipes')
                ->where('users_id', '=', $user->id)
                ->where('ur_date', '=', Carbon::now()->format('Y-m-d'))
                ->get();
            }
        }
        return $result;       
    }

    public function updateUr(String $meal){
        $user = auth()->user();
        $recipeClass = new RecipeController();
        $urClass = new UserRecipeController();
        $temp = $recipeClass->count_recipe();
        $updateId = 0;

        switch($meal){
            case 'ur_breakfast':
                $updateId = $urClass->randomMealSelector($temp[0]);
                break;
            case 'ur_lunch':
                $updateId = $urClass->randomMealSelector($temp[1]);
                break;
            case 'ur_snacks':
                $updateId = $urClass->randomMealSelector($temp[2]);
                break;
            case 'ur_dinner':
                $updateId = $urClass->randomMealSelector($temp[3]);
                break;
        }

        $success = User_recipe::where('users_id','=', $user->id)
        ->where('ur_date','=', Carbon::now()->format('Y-m-d'))
        ->update(array($meal => $updateId) );

        if($success){
            $result = DB::table('user_recipes')
            ->where('users_id', '=', $user->id)
            ->where('ur_date', '=', Carbon::now()->format('Y-m-d'))
            ->get();

            return $result;
        }
    }

    public function randomMealSelector(object $temp){

        $result = [];

        for($i = 0; $i < count($temp); $i++)
            array_push($result,$temp[$i]);

        $meal_id = $result[array_rand((array)$result,1)];
        
        return $meal_id;
    }
}
