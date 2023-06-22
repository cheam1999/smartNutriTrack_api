<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Ingredients;
use Carbon\Carbon;

class IngredientsController extends Controller
{
    public function generate_daily_grocery(Request $request){
        $meal_recipe = $request->validate([            
            'breakfast' => 'required',
            'lunch' => 'required',
            'snacks' => 'required',
            'dinner'  => 'required',            
        ]);

        $breakfast = DB::table('ingredients')
        ->where('recipe_id', '=', $meal_recipe['breakfast'])
        ->get();

        $lunch = DB::table('ingredients')
        ->where('recipe_id', '=', $meal_recipe['lunch'])
        ->get();

        $breakfast = json_decode($breakfast);
        $lunch = json_decode($lunch);
        $combine = array_merge(
            (array) $breakfast, (array) $lunch);

            // function checkSimilar(){

            // }
        // $similar = [];
        $ing = [];

        for ($x = 0; $x < count($combine); $x++) {
            for ($y = $x + 1; $y < count($combine); $y++) {
                $temp = strcmp($combine[$x]->ingredients_name,$combine[$y]->ingredients_name);

                if($temp == 0){ // same ingredient
                    if($ing == []){ // empty array
                        $result = doubleval($combine[$x]->amount) + doubleval($combine[$y]->amount);
                        $addOn = [
                            'ingredients_name' => $combine[$x]->ingredients_name,
                            'amount' =>  number_format($result),
                            'measure_name' => $combine[$x]->measure_name,
                        ];

                        array_push($ing, $addOn);
                    }else{
                        for ($z = 0; $z < count($ing); $z++) {
                            $tempIng = strcmp($combine[$y]->ingredients_name,$ing[$z]['ingredients_name']); 

                            if($tempIng == 0){ // ingredient insert before
                                $result = doubleval($ing[$z]['amount']) + doubleval($combine[$y]->amount);
                                $ing[$z]['amount'] = number_format($result);
                                break;
                            }else{ // ingredient not insert before
                                $result = doubleval($combine[$x]->amount) + doubleval($combine[$y]->amount);
                                $addOn = [
                                    'ingredients_name' => $combine[$x]->ingredients_name,
                                    'amount' =>  number_format($result),
                                    'measure_name' => $combine[$x]->measure_name,
                                ];
        
                                array_push($ing, $addOn);
                            }
                        }
                    }
                }
            }

            $insertBefore = false;

            for ($z = 0; $z < count($ing); $z++) {
                $tempIng = strcmp($combine[$x]->ingredients_name,$ing[$z]['ingredients_name']); 

                if($tempIng == 0)
                    $insertBefore = true;
            }

            if($insertBefore == false){
                $addOn = [
                    'ingredients_name' => $combine[$x]->ingredients_name,
                    'amount' =>  number_format($combine[$x]->amount),
                    'measure_name' => $combine[$x]->measure_name,
                ];

                array_push($ing, $addOn);
            }
        }

        return $ing;
    }

    public function index()
    {
        $ing = DB::table('ingredients')
        ->where('archived', '=', 0)
        ->get();

        return $ing;
    }

    public function create(Request $request)
    {
        $user = auth()->user();

        $validatedData = $request->validate([
            'ingredients_name' => 'required|string|max:255',
            'recipe_id' => 'required',
            'amount' => 'required',
            'measure_name' => 'string',
            'cups' => 'required',
            'comments' => '',
        ]);

        $success = Ingredients::create([
            'ingredients_name' => strtolower($validatedData['ingredients_name']),
            'recipe_id' => $validatedData['recipe_id'],
            'amount' => $validatedData['amount'],
            'measure_name' => $validatedData['measure_name'],
            'cups' => $validatedData['cups'],
            'comments' => $validatedData['comments'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ])->save();

        

        return $success;
    }

    public function get_ing_by_id(string $id)
    {
        $retrieved_ing = DB::table('ingredients')
                    ->where('id', '=', $id)
                    ->get();

        return $retrieved_ing;
    }

    public function archivedIng(string $id){

        $archived_ing = DB::table('ingredients')
        ->where('id', '=', $id)
        ->update(array('archived' => "1"));

        return $archived_ing;
    }

    public function update_ing(Request $request){
        // $user = auth()->user();

        $validatedData = $request->validate([
            'id' => 'required',
            'ingredients_name' => 'required|string|max:255',
            'recipe_id' => 'required',
            'amount' => 'required',
            'measure_name' => 'string',
            'cups' => 'required',
            'comments' => '',
        ]);

        $success = Ingredients::where('id', $validatedData['id'])->update([
            'ingredients_name' => strtolower($validatedData['ingredients_name']),
            'recipe_id' => $validatedData['recipe_id'],
            'amount' => $validatedData['amount'],
            'measure_name' => $validatedData['measure_name'],
            'cups' => $validatedData['cups'],
            'comments' => $validatedData['comments'],
            'updated_at' => Carbon::now(),
        ]);
        

        return $success;
    }
}
