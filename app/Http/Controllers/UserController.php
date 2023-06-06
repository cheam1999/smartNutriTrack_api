<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use DB;

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
            'password' => bcrypt($fields['password'],     
            )
        ]);

        $user =  User::withSelectUser()
            ->where('users.id', $newUser->id)
            ->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        $user->accessToken = $token;
        $user->tokenType = 'Bearer';
        $user->isLogin = true;

        $result = [[
            "id" => $user->id,
            "name"=> $user->name,
            "email"=> $user->email,
            "accessToken" => $token,
            "tokenType"=> "Bearer",
            "isLogin"=> true
        ]];

        return $result;
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

        $result = [[
            "id" => $user->id,
            "name"=> $user->name,
            "email"=> $user->email,
            "accessToken" => $token,
            "tokenType"=> "Bearer",
            "isLogin"=> true
        ]];

        return $result;
    }

    public function me(Request $request)
    {


        // $user_id = $request->user()->id;
        $_user = auth()->user();
        $user = User::where('id', '=', $_user->id)->get();

        // $user->accessToken = request()->user()->currentAccessToken()->token;
        // $user->tokenType = 'Bearer';

        // $user->isLogin = true;

        $result = [[
            "id" => $user[0]['id'],
            "name"=> $user[0]['name'],
            "email"=> $user[0]['email'],
            "accessToken" => request()->user()->currentAccessToken()->token,
            "tokenType"=> 'Bearer',
            "isLogin"=> true
        ]];

        return $result;
    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged Out'
        ];
    }

    public function updateProfileDetails(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
        ]);

        // $user = $request->user();

        // $success = $user->update([
        //     'username' => $validatedData['username'],
        //     'email' => $validatedData['email'],
        // ]);

        $user = auth()->user();
        $updatedUser = User::where('users.id', '=', $user->id)
            ->update(array('name' => $validatedData['name'],'email' => $validatedData['email'], ) );

        return $updatedUser;
    }
}
