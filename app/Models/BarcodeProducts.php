<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarcodeProducts extends Model
{
    use HasFactory;

    protected $table = 'barcode_products';
    protected $primaryKey = 'food_code';

    protected $fillable = [
        'food_name',
        'food_quantity',
        'food_serving_size',
        'energy_kcal_100g',
        'carbohydrates_100g',
        'proteins_100g',
        'fat_100g',
        'sodium_100g',
        'vitamin_D_100g',
        'calcium_100g',
        'iron_100g',
    ];

    public function scopeWithSelectProducts($query)
    {
        return $query->select(
            'food_code as foodCode',
            'food_name as foodName',
            'food_quantity as foodQuantity',
            'food_serving_size as foodServingSize',
            'energy_kcal_100g as energyKcal',
            'carbohydrates_100g as carbohydrates',
            'proteins_100g as proteins',
            'fat_100g as fat',
            'sodium_100g as sodium',
            'vitamin_D_100g as vitaminD',
            'calcium_100g as calcium',
            'iron_100g as iron',
        );
    }
}
