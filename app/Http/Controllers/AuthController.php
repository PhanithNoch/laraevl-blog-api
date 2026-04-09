<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // 1. validate 
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);
        // 2. password (123)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        //3. insert to database then response to client 

        return response()->json([
            'message' => "Registered successful",
            'user' => $user
        ], 200);
    }

    public function login(Request $request)
    {
        // 1. validate 
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Email or password invalid'
            ], 401);
        }

        $token = Auth::user()->createToken('laravel-blog-api')->plainTextToken; //key can be expired 

        // token -> ID Card 
        return response()->json([
            'message' => 'login successfuly',
            'data' => Auth::user(), // user info 
            'token' => $token

        ]);
    }
}
