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
        'food_verified',
        'food_created_by',
        'food_verified_by'
    ];

    public function scopeWithSelectFood($query)
    {
        return $query->leftJoin('users', 'food.food_created_by', '=', 'users.id')
        ->leftJoin('users', 'food.food_verified_by', '=', 'users.id')
        ->select(
            'id as id',
            'food_code as food_code',
            'food_name as food_name',
            'food_quantity as food_quantity',
            'food_serving_size as food_serving_size',
            'energy_kcal_100g as energy_kcal_100g',
            'carbohydrates_100g as carbohydrates_100g',
            'proteins_100g as proteins_100g',
            'sodium_100g as sodium_100g',
            'calcium_100g as calcium_100g',
            'food_verified as food_verified',
            'archived as archived'
        );
    }

    


}
