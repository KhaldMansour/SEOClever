<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Http\Controllers\Controller;


use Illuminate\Http\Request;

class UserController extends Controller
{
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth('user')->attempt($credentials)) 
        {
            return response()->json(['error' => "Email or Password doesn't exist"], 401);
        }

        return $this->respondWithToken($token); 
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password'=>'required|min:6'
        ]);

        $user = User::create($data);

        return $this->login($user);
    }

    public function myProfile()
    {
       return auth('user')->user();
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => auth('user')->user()->name
        ]);
    }
}
