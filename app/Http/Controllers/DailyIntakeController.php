<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyIntake;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class DailyIntakeController extends Controller
{
    public function getDailySummary()
    {
        $user = auth()->user();

        $daily =  DailyIntake::select('daily_intakes.*')
        ->where('daily_intakes.d_intake_date', Carbon::now()->format('Y-m-d'))
        ->where('daily_intakes.users_id', $user->id)
        ->get();

        // Call api to calculate level
        $response = Http::post(
            'http://127.0.0.1:8000/dietary_score',
            [
                'Carbohydrate' => $daily[0]->d_carbohydrates / $daily[0]->d_energy_kcal * 100,
                'Protein'=> $daily[0]->d_proteins,
                'Sodium'=> $daily[0]->d_sodium,
                'Calcium'=> $daily[0]->d_calcium
            ]
        );

        $result = 
        [
            'carb_val'=> $daily[0]->d_carbohydrates / $daily[0]->d_energy_kcal * 100,
            'carb_level' => $response[2],
            'protein_val' => $daily[0]->d_proteins,
            'protein_level' => $response[6],
            'sodium_val' => $daily[0]->d_sodium,
            'sodium_level' => $response[10],
            'calcium_val' => $daily[0]->d_calcium,
            'calcium_level' => $response[14]
        ];
        

        return json_encode($result);

    }
}
