<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', 'App\Http\Controllers\API\AuthController@login');
Route::post('/register', 'App\Http\Controllers\API\AuthController@register');
Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'getUser']);

Route::resource('user', 'App\Http\Controllers\API\UsersController');
