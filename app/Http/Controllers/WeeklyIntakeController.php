<?php

namespace App\Http\Controllers;
use App\Models\WeeklyIntake;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class WeeklyIntakeController extends Controller
{
    public function getWeeklySummary()
    {
        $user = auth()->user();

        $weekly =  WeeklyIntake::select('weekly_intakes.*')
        ->latest('weekly_intakes.W_sun_date')
        ->first();

        $day = Carbon::now()->weekday() +1;
        $avg_carb = $weekly->w_carbohydrates / $weekly->w_energy_kcal * 100 /$day;
        $avg_proteins = $weekly->w_proteins / $day;
        $avg_sodium = $weekly->w_sodium / $day;
        $avg_calcium = $weekly->w_calcium / $day;
        // Call api to calculate level
        $response = Http::post(
            'http://127.0.0.1:8000/dietary_score',
            [
                'Carbohydrate' => $avg_carb,
                'Protein'=> $avg_proteins,
                'Sodium'=> $avg_sodium,
                'Calcium'=> $avg_calcium
            ]
        );

        $temp = json_decode($response);

        $result = 
        [
            'carb_val'=> $avg_carb,
            'carb_level' => $response[2],
            'protein_val' => $avg_proteins,
            'protein_level' => $response[6],
            'sodium_val' => $avg_sodium,
            'sodium_level' => $response[10],
            'calcium_val' => $avg_calcium,
            'calcium_level' => $response[14],
            'score' => substr($temp,17,-1) ,
            'sun_date' => $weekly->w_sun_date,
        ];
        

        return json_encode($result);

    }

    public function getDiabetes(string $carb){
        $response = Http::post(
            'http://127.0.0.1:8010/diabetes_prediction',
            [
                'Carbohydrate_intake' => doubleval($carb),
            ]);
            
        return $response;
    }
}
