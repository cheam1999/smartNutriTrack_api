<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;
use DB;

class FoodController extends Controller
{
    public function index()
    {
        return Food::withSelectFood()->get();
    }

    public function get_barcode_products(string $code)
    {
        $scanned_food = DB::table('food')
                    ->where('food_code', '=', $code)
                    ->get();

        return $scanned_food;
    }
}
