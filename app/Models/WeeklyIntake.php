<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyIntake extends Model
{
    use HasFactory;

    protected $table = 'weekly_intakes';
    protected $primaryKey = 'id';

    protected $fillable = [
        'w_energy_kcal',
        'w_carbohydrates',
        'w_proteins',
        'w_sodium',
        'w_calcium',
        'w_sun_date',
        'users_id',
    ];

    public function scopeWithSelectProducts($query)
    {
        return $query->leftJoin('users', 'weekly_intake.users_id', '=', 'users.id')
        ->select(
            'id as id',
            'w_energy_kcal as dEnergyKcal',
            'w_carbohydrates as dCarbohydrates',
            'w_proteins as dProteins',
            'w_sodium as dSodium',
            'w_calcium as sCalcium',
            'w_sun_date as dSunDate',
        );
    }
}
