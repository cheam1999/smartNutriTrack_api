<?php

namespace App\Http\Controllers;
use App\Models\User_recipe;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
            'created_at' => Carbon::now(),
            'update_at' => Carbon::now(),
        ])->save();

        

        return $success;
    }
}
