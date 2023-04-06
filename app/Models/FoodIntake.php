<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodIntake extends Model
{
    use HasFactory;

    protected $table = 'food_intakes';
    protected $primaryKey = 'id';

    protected $fillable = [
        'meal',
        'intake_serving_size',
        'users_id',
        'food_id',
        'intake_date',
    ];

    public function scopeWithSelectProducts($query)
    {
        return $query->leftJoin('users', 'food_intake.users_id', '=', 'users.id')
        ->leftJoin('food', 'food_intake.food_id', '=', 'food.id')
        ->select(
            'id as id',
            'meal as meal',
            'intake_serving_size as intakeServingSize',
            'intake_date as intakeDate',
        );
    }
}
