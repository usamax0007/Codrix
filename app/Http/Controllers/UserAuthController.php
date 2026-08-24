<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use Illuminate\Support\Facades\Auth;

class UserAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('frontend.user.login');
    }

    public function login(UserLoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();
            
            // Check if user is regular user (not Super Admin or Admin)
            if ($user->isSuperAdmin() || $user->isAdmin()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Super Admin and Admin should use the admin portal at /admin/login',
                ])->withInput($request->except('password'));
            }

            $request->session()->regenerate();
            return redirect()->intended(route('user.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->except('password'));
    }

    public function logout(\Illuminate\Http\Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }

    public function dashboard()
    {
        return view('frontend.user.dashboard');
    }
}