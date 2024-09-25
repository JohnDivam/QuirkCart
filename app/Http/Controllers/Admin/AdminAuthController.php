<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminAuthController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Auth/Login');
    }

    public function login(Request $request)
    {
        #validate request data
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        #credentials
        $credentials = $request->only('email', 'password');
        $credentials['isAdmin'] = true;

        if (Auth::attempt($credentials)) {
            return redirect()->route('admin.dashboard');
        }

        #Redirect back with an error if login fails
        return redirect()->route('admin.login')->withErrors(['error' => 'Invalid credentials.']);    
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // return redirect('/');
        return redirect()->route('admin.login');
    }
}