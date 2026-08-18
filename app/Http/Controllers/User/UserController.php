<?php

namespace App\Http\Controllers\User;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StorePortalUserRequest;
use App\Models\User;
use App\Services\User\UserManagementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(private readonly UserManagementService $users) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', User::class);

        return view('user.users.index', [
            'users' => $this->users->paginateForPortal(),
            'roleOptions' => UserRole::assignableOptions(),
        ]);
    }

    public function store(StorePortalUserRequest $request): RedirectResponse
    {
        $this->users->create($request->userData());

        return redirect()
            ->route('user.users.index')
            ->with('success', 'User created successfully.');
    }
}
