<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;
use DB;
use Carbon\Carbon;


class RecipeController extends Controller
{
    public function index()
    {
        $recipe = DB::table('recipe')
        ->where('archived', '=', 0)
        ->get();

        return $recipe;
    }

    public function create(Request $request)
    {
        $user = auth()->user();

        $validatedData = $request->validate([
            'recipe_name' => 'required|string|max:255',
            'recipe_image' => 'string|max:255',
            'recipe_ingredients' => 'string',
            'recipe_instructions' => 'string',
            'recipe_source' => 'string|max:255',
            'recipe_meal' => 'required',
        ]);

        $success = Recipe::create([
            'recipe_name' => $validatedData['recipe_name'],
            'recipe_image' => $validatedData['recipe_image'],
            'recipe_ingredients' => $validatedData['recipe_ingredients'],
            'recipe_instructions' => $validatedData['recipe_instructions'],
            'recipe_source' => $validatedData['recipe_source'],
            'recipe_meal' => $validatedData['recipe_meal'],
            'recipe_created_by' => $user->id,
            'created_at' => Carbon::now(),
            'update_at' => Carbon::now(),
        ])->save();

        

        return $success;
    }

    public function get_recipe_by_id(string $id)
    {
        $retrieved_recipe = DB::table('recipe')
                    ->where('recipe_id', '=', $id)
                    ->get();

        return $retrieved_recipe;
    }

    public function get_breakfast_recipe()
    {
        $retrieved_recipe = DB::table('recipe')
                    ->where('recipe_meal', '=', 0)
                    ->get();

        return $retrieved_recipe;
    }

    public function get_lunch_recipe()
    {
        $retrieved_recipe = DB::table('recipe')
                    ->where('recipe_meal', '=', 1)
                    ->get();

        return $retrieved_recipe;
    }

    public function count_recipe()
    {
        $breakfast_num = Recipe::where('recipe_meal' ,'=', 0)->pluck('recipe_id');
        $lunch_num = Recipe::where('recipe_meal' ,'=', 1)->pluck('recipe_id');
        $snacks_num = Recipe::where('recipe_meal' ,'=', 2)->pluck('recipe_id');
        $dinner_num = Recipe::where('recipe_meal' ,'=', 3)->pluck('recipe_id');

        $result = [$breakfast_num,$lunch_num,$snacks_num,$dinner_num];

        return $result;
    }

    public function update_recipe(Request $request){
        $user = auth()->user();

        $validatedData = $request->validate([
            'recipe_id'=> 'required',
            'recipe_name' => 'required|string|max:255',
            'recipe_image' => 'string|max:255',
            'recipe_ingredients' => 'string',
            'recipe_instructions' => 'string',
            'recipe_source' => 'string|max:255',
            'recipe_meal' => 'required',
        ]);

        $success = Recipe::where('recipe_id', $validatedData['recipe_id'])->update([
            'recipe_name' => $validatedData['recipe_name'],
            'recipe_image' => $validatedData['recipe_image'],
            'recipe_ingredients' => $validatedData['recipe_ingredients'],
            'recipe_instructions' => $validatedData['recipe_instructions'],
            'recipe_source' => $validatedData['recipe_source'],
            'recipe_meal' => $validatedData['recipe_meal'],
            'recipe_created_by' => $user->id,
            'updated_at' => Carbon::now(),
        ]);

        

        return $success;
    }

    public function archivedRecipe(string $id){

        $archived_recipe = DB::table('recipe')
        ->where('recipe_id', '=', $id)
        ->update(array('archived' => "1"));

        $archived_ingredient = DB::table('ingredients')
        ->where('recipe_id', '=', $id)
        ->update(array('archived' => "1"));

       if($archived_recipe && $archived_ingredient){
        return true;
       }else{
        return false;
       }
    }
}
