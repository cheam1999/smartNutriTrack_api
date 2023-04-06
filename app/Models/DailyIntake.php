<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyIntake extends Model
{
    use HasFactory;

    protected $table = 'daily_intakes';
    protected $primaryKey = 'id';

    protected $fillable = [
        'd_energy_kcal',
        'd_carbohydrates',
        'd_proteins',
        'd_sodium',
        'd_calcium',
        'd_intake_date',
        'users_id',
    ];

    public function scopeWithSelectProducts($query)
    {
        return $query->leftJoin('users', 'daily_intakes.users_id', '=', 'users.id')
        ->select(
            'id as id',
            'd_energy_kcal as dEnergyKcal',
            'd_carbohydrates as dCarbohydrates',
            'd_proteins as dProteins',
            'd_sodium as dSodium',
            'd_calcium as dCalcium',
            'd_intake_date as dIntakeDate',
        );
    }
}
