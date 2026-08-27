<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminLoginRequest;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('frontend.admin.login');
    }

    public function login(AdminLoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();
            
            if (!$user->isSuperAdmin() && !$user->isAdmin()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Only Super Admin and Admin can access this portal.',
                ])->withInput($request->except('password'));
            }

            $request->session()->regenerate();
            

            if ($user->isSuperAdmin()) {
                return redirect()->intended(route('super-admin.dashboard'));
            }
            
            return redirect()->intended(route('admin.dashboard'));
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
        return view('frontend.admin.dashboard');
    }
}
