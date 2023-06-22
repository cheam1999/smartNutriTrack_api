<?php

namespace App\Http\Controllers;

use App\Models\FoodIntake;
use App\Models\DailyIntake;
use App\Models\WeeklyIntake;
use App\Models\Food;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class FoodIntakeController extends Controller
{
    public function getCurrentMeals()
    {
        $user = auth()->user();

        return FoodIntake::select('food_intakes.*','food.food_name as food_name')
        ->leftJoin('food', 'food_intakes.food_id', '=', 'food.id')
        ->where('food_intakes.intake_date', Carbon::now()->format('Y-m-d'))
        ->where('food_intakes.users_id', $user->id)
        ->get();
    }

    public function save_meal(Request $request){

        $user = auth()->user();
        $fields = $request->validate([
            'meal' => 'required',
            'intake_serving_size' => 'required',
            'food_id' => 'required'
        ]);

        # Create new entry at food intake table
        $newFoodIntake = FoodIntake::create([
            'meal' => $fields['meal'],
            'intake_serving_size' => $fields['intake_serving_size'],
            'users_id' => $user->id,
            'food_id' => $fields['food_id'],
            'intake_date' => Carbon::now()->format('Y-m-d')

        ])->save();

        # Get food nutrition details
        $food = json_decode(Food::where('id','=', $fields['food_id'])->get());
        
        # Check entry in daily_intake
        $daily_check = DailyIntake::where('d_intake_date', '=', Carbon::now()->format('Y-m-d'))-> exists();
        
        # Get 
        $finalProportion = $fields['intake_serving_size'];
        $finalCarb = $finalProportion * $food[0]->carbohydrates_100g;
        $finalProtein = $finalProportion * $food[0]->proteins_100g;
        $finalSodium = $finalProportion * $food[0]->sodium_100g;
        $finalCalcium = $finalProportion * $food[0]->calcium_100g;

        if(!$daily_check){ # No daily entry
            
            $daily_success = DailyIntake::create([
                'd_energy_kcal' => $food[0]->energy_kcal_100g,
                'd_carbohydrates' => $finalCarb,
                'd_proteins'  => $finalProtein,
                'd_sodium'  => $finalSodium,
                'd_calcium'  => $finalCalcium,
                'd_intake_date' => Carbon::now()->format('Y-m-d'),
                'users_id' => $user->id,
            ])->save();
        }else{
            $daily_old = json_decode(DailyIntake::where('users_id','=', $user->id)
            ->where('d_intake_date','=',Carbon::now()->format('Y-m-d'))
            ->get());

            $new_energy = $daily_old[0]->d_energy_kcal +  $food[0]->energy_kcal_100g;
            $new_carb = $daily_old[0]->d_carbohydrates + $finalCarb;
            $new_protein = $daily_old[0]->d_proteins + $finalProtein;
            $new_sodium = $daily_old[0]->d_sodium + $finalSodium;
            $new_calcium = $daily_old[0]->d_calcium + $finalCalcium;

            $daily_success = DailyIntake::where('users_id','=', $user->id)
            ->where('d_intake_date','=',Carbon::now()->format('Y-m-d'))
            ->update([
                'd_energy_kcal' => $new_energy,
                'd_carbohydrates' => $new_carb,
                'd_proteins'  => $new_protein,
                'd_sodium'  => $new_sodium,
                'd_calcium'  => $new_calcium,
                ]);
        }

        # Check entry in weekly intake
        $check_sun_date =  json_decode(WeeklyIntake::where('users_id','=', $user->id)
        ->latest('w_sun_date')
        ->first());

        $weekday = Carbon::now()->weekday();
        $add_num_days = 7 - $weekday;
        $today = Carbon::now()->format('Y-m-d');

        if(!$check_sun_date){ # no entry before, create a new entry

            $weekly_success = WeeklyIntake::create([
                'w_energy_kcal' => $food[0]->energy_kcal_100g,
                'w_carbohydrates' => $finalCarb,
                'w_proteins'  => $finalProtein,
                'w_sodium'  => $finalSodium,
                'w_calcium'  => $finalCalcium,
                'w_sun_date' => Carbon::now()->addDays($add_num_days)->format('Y-m-d'),
                'users_id' => $user->id,
            ])->save();
        }else{ # entry exist for user
            if($today < $check_sun_date->w_sun_date ){ # in the same week, increment value
                $new_energy_w = $check_sun_date->w_energy_kcal +  $food[0]->energy_kcal_100g;
                $new_carb_w = $check_sun_date->w_carbohydrates + $finalCarb;
                $new_protein_w = $check_sun_date->w_proteins + $finalProtein;
                $new_sodium_w = $check_sun_date->w_sodium + $finalSodium;
                $new_calcium_w = $check_sun_date->w_calcium + $finalCalcium;

                $weekly_success = WeeklyIntake::where('users_id','=', $user->id)
                ->where('w_sun_date','=',$check_sun_date->w_sun_date)
                ->update([
                    'w_energy_kcal' => $new_energy_w,
                    'w_carbohydrates' => $new_carb_w,
                    'w_proteins'  => $new_protein_w,
                    'w_sodium'  => $new_sodium_w,
                    'w_calcium'  => $new_calcium_w,
                    ]);

            }else{ # is sunday, add new entry
                $weekly_success = WeeklyIntake::create([
                    'w_energy_kcal' => $food[0]->energy_kcal_100g,
                    'w_carbohydrates' => $finalCarb,
                    'w_proteins'  => $finalProtein,
                    'w_sodium'  => $finalSodium,
                    'w_calcium'  => $finalCalcium,
                    'w_sun_date' => Carbon::now()->addDays($add_num_days)->format('Y-m-d'),
                    'users_id' => $user->id,
                ])->save();

            }

        }
        


        return $newFoodIntake;
    }

    public function delete_meal(Request $request){

        $daily_success = false;
        $weekly_success = false;

        $user = auth()->user();
        $fields = $request->validate([
            'food_intake_id' => 'required'
        ]);

      

        # Get food nutrition details
        $foodIntake = json_decode(FoodIntake::where('id','=', $fields['food_intake_id'])->get());
        // return $foodIntake[0]->food_id;
        $food = json_decode(Food::where('id','=', $foodIntake[0]->food_id)->get());
        // print($foodIntake['food_id']);
        // # Check entry in daily_intake
        $daily_data = json_decode(DailyIntake::where('users_id','=',$foodIntake[0]->users_id)
        ->where('d_intake_date', '=', Carbon::now()->format('Y-m-d'))
        ->get());
        
        # Get 
        $finalProportion = $foodIntake[0]->intake_serving_size;
        $finalCarb = $finalProportion * $food[0]->carbohydrates_100g;
        $finalProtein = $finalProportion * $food[0]->proteins_100g;
        $finalSodium = $finalProportion * $food[0]->sodium_100g;
        $finalCalcium = $finalProportion * $food[0]->calcium_100g;

        if($daily_data != null){ # No daily entry
            
            $daily_success = DailyIntake::where('id', '=', $daily_data[0]->id)
            ->update(array(
                'd_energy_kcal' => $daily_data[0]->d_energy_kcal - $food[0]->energy_kcal_100g, 
                'd_carbohydrates' => $daily_data[0]->d_carbohydrates - $finalCarb,
                'd_proteins' => $daily_data[0]->d_proteins - $finalProtein,
                'd_sodium' => $daily_data[0]->d_sodium - $finalSodium,
                'd_calcium' => $daily_data[0]->d_calcium - $finalCalcium
            ));
        
        }

        // # Check entry in weekly intake
        $check_sun_date =  json_decode(WeeklyIntake::where('users_id','=', $foodIntake[0]->users_id)
        ->latest('w_sun_date')
        ->first());

        if($check_sun_date != null){ 
            $weekly_success = WeeklyIntake::where('id', '=', $check_sun_date->id)
                ->update(array(
                    'w_energy_kcal' => $check_sun_date->w_energy_kcal - $food[0]->energy_kcal_100g, 
                    'w_carbohydrates' => $check_sun_date->w_carbohydrates - $finalCarb,
                    'w_proteins' => $check_sun_date->w_proteins - $finalProtein,
                    'w_sodium' => $check_sun_date->w_sodium - $finalSodium,
                    'w_calcium' => $check_sun_date->w_calcium - $finalCalcium
                ));
        
        }

        

        print($daily_success);
        print($weekly_success);

        if($daily_success && $weekly_success){
            # delete row from food intake
            print("true");
            $food_intake_success = DB::table('food_intakes')->where('id','=', $fields['food_intake_id'])->delete();
            
            if($food_intake_success){
                return true;
            }else{
                return false;
            }
        }
        else{
            print("false");
            return false;
        }

        
    }
}
