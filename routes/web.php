<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', 'App\Http\Controllers\PagesController@login');
Route::post('/login-post', 'App\Http\Controllers\PagesController@loginPost');
Route::get('/register', 'App\Http\Controllers\PagesController@register');
Route::get('/home', 'App\Http\Controllers\PagesController@home');
Route::get('/todo', 'App\Http\Controllers\PagesController@todo');
