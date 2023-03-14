<?php

namespace App\Http\Controllers;
use App\Models\BarcodeProducts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarcodeProductsController extends Controller
{
    public function index()
    {
        return BarcodeProducts::all();
    }

    public function show(string $code)
    {
        $scanned_food = DB::table('barcode_products')
                    ->where('food_code', '=', $code)
                    ->get();

        return $scanned_food;
    }

}
