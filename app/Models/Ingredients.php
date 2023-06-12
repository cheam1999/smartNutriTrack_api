<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredients extends Model
{
    protected $table = 'ingredients';
    protected $primaryKey = 'id';

    protected $fillable = [
        'ingredients_name',
        'recipe_id',
        'amount',
        'measure_name',
        'cups',
        'comments',
        'archived'
    ];
}
