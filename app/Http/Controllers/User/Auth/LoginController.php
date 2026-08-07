<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Services\User\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function __construct(private readonly AuthService $auth) {}

    public function create(): View|RedirectResponse
    {
        if (auth()->check()) {
            return redirect()->to(
                auth()->user()->isAdmin() ? '/admin' : route('user.dashboard')
            );
        }

        return view('user.auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $this->auth->attempt(
            $request->email(),
            $request->password(),
            $request->remember(),
        );

        return redirect()->intended(route('user.dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $this->auth->logout($request);

        return redirect()->route('user.login');
    }
}
