<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function loginPost()
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            return redirect()->intended('/home'); 
        }

        return redirect()->back()->with('error', 'Invalid credentials.');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function home()
    {
        return view('home');
    }

    public function todo()
    {
        return view('todo');
    }
}
