<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_recipe extends Model
{
    use HasFactory;

    protected $table = 'user_recipes';
    protected $primaryKey = 'id';

    protected $fillable = [
        'ur_breakfast',
        'ur_lunch',
        'ur_snacks',
        'ur_dinner',
        'ur_date',
        'users_id'
    ];

    public function scopeWithSelectUserRecipe($query)
    {
        return $query->leftJoin('users', 'user_recipes.users_id', '=', 'users.id')
        ->select(
            'id as id',
            'ur_breakfast as ur_breakfast',
            'ur_lunch as ur_lunch',
            'ur_snacks as ur_snacks',
            'ur_dinner as ur_dinner',
            'ur_date as ur_date'
        );
    }
}
