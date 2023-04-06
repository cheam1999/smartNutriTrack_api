<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request){
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $newUser = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $user =  User::withSelectUser()
            ->where('users.id', $newUser->id)
            ->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        $user->accessToken = $token;
        $user->tokenType = 'Bearer';
        $user->isLogin = true;

        return $user;
    }


    public function login(Request $request){
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', '=', $fields['email'])->firstOrFail();
        
        if(!$user || !Hash::check($fields['password'],$user->password)){
            return response([
                'message' => 'Unauthenticated'
            ], 401);
        }


        $token = $user->createToken('auth_token')->plainTextToken;

        $user->accessToken = $token;
        $user->tokenType = 'Bearer';
        $user->isLogin = true;

        return $user;
    }

    public function me(Request $request)
    {

        $user_id = $request->user()->id;
        $user = auth()->user();
        $user = User::withSelectUser()
            ->where('users.id', $user_id)
            ->firstOrFail();

        // $user->accessToken = request()->user()->currentAccessToken()->token;
        // $user->tokenType = 'Bearer';

        $user->isLogin = true;

        return $user;
    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged Out'
        ];
    }
}
