<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', 'App\Http\Controllers\PagesController@login');
Route::get('/register', 'App\Http\Controllers\PagesController@register');
Route::get('/home', 'App\Http\Controllers\PagesController@home');
