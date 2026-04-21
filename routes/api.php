<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Middleware\EnsureTokenIsValid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('register',[AuthController::class,'register']);


Route::post('login',[AuthController::class,'login'])->middleware(EnsureTokenIsValid::class);

Route::post('logout',function(){
    $user = Auth::user(); // access current user logged In 
    $user->tokens()->delete();
    return response()->json([
        'message'=> "logout successfuly",
        
    ],200);

})->middleware('auth:sanctum');

/// middleware and controller 

// POST ROUTES
Route::get('posts',[PostController::class,'index']); // public route to get all posts
Route::post('posts',[PostController::class,'store'])->middleware('auth:sanctum'); // protected route to create a new post
Route::post('posts/{id}',[PostController::class,'update']); // replace  
Route::delete('posts/{id}',[PostController::class,'destroy']);