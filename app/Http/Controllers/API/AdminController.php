<?php

namespace App\Http\Controllers\API;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth('admin')->attempt($credentials)) 
        {
            return response()->json(['error' => "Email or Password doesn't exist"], 401);
        }

        return $this->respondWithToken($token); 
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:admins',
            'password'=>'required|min:6'
        ]);

        $admin = Admin::create($data);

        return $this->login($admin);
    }

    public function myProfile()
    {
       return auth('admin')->user();
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'admin' => auth('admin')->user()->name
        ]);
    }
}
