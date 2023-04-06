<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    protected $table = 'food';
    protected $primaryKey = 'id';

    protected $fillable = [
        'food_code',
        'food_name',
        'food_quantity',
        'food_serving_size',
        'energy_kcal_100g',
        'carbohydrates_100g',
        'proteins_100g',
        'sodium_100g',
        'calcium_100g',
    ];

    public function scopeWithSelectProducts($query)
    {
        return $query->select(
            'id as id',
            'food_code as foodCode',
            'food_name as foodName',
            'food_quantity as foodQuantity',
            'food_serving_size as foodServingSize',
            'energy_kcal_100g as energyKcal',
            'carbohydrates_100g as carbohydrates',
            'proteins_100g as proteins',
            'sodium_100g as sodium',
            'calcium_100g as calcium',
        );
    }
}
