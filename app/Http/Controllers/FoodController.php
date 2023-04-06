<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class FoodController extends Controller
{
    public function index()
    {
        return Food::all();
    }

    public function show(string $code)
    {
        $scanned_food = DB::table('food')
                    ->where('food_code', '=', $code)
                    ->get();

        return $scanned_food;
    }
}
