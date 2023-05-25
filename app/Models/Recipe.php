<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;
    protected $table = 'recipe';
    protected $primaryKey = 'recipe_id';

    protected $fillable =[
        'recipe_name',
        'recipe_image',
        // 'recipe_level',
        'recipe_ingredients',
        'recipe_instructions',
        'recipe_source',
        'recipe_meal',
        // 'recipe_video',
    ];

    public function scopeWithSelectRecipe($query)
    {
            return $query->select(
                'recipe_id as recipeID',
                'recipe_name as recipeName',
                'recipe_image as recipeImage',
                // 'recipe_level as recipeLevel',
                'recipe_ingredients as recipeIngredients',
                'recipe_instructions as recipeInstructions',
                'recipe_source as recipeSource',
                'recipe_meal as recipe_meal'
                // 'recipe_video as recipeVideo',
            );
    }
}
