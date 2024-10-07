<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', 'App\Http\Controllers\API\AuthController@login');
Route::post('/register', 'App\Http\Controllers\API\AuthController@register');
Route::middleware('auth:sanctum')->get('/current-user', function (Request $request) {
    return response()->json($request->user());
});

Route::resource('user', 'App\Http\Controllers\API\UsersController');
Route::resource('todo', 'App\Http\Controllers\API\TodosController');
