<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'name'      => 'required|string',
            'password'  => 'required|min:6',
        ]);
    
        if (Auth::attempt($request->only('name', 'password'))) {
            return redirect()->intended('/');
        }
    
        return back()->withErrors([
            'name' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ]);
    }
    

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}

