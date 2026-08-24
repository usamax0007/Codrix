<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    public function index()
    {
        $this->authorize('view-users');

        $users = User::with('roles')->get();
        $assignableRoles = auth()->user()->getAssignableRoles();

        // Return admin view if accessed from admin routes
        if (request()->is('admin/*')) {
            return view('frontend.admin.users.index', compact('users', 'assignableRoles'));
        }

        return view('frontend.user.users.index', compact('users', 'assignableRoles'));
    }

    public function create()
    {
        $this->authorize('create-users');

        $assignableRoles = auth()->user()->getAssignableRoles();
        $roles = Role::whereIn('name', $assignableRoles)->get();

        // Return admin view if accessed from admin routes
        if (request()->is('admin/*')) {
            return view('frontend.admin.users.create', compact('roles'));
        }

        return view('frontend.user.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $this->authorize('create-users');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|exists:roles,name',
        ]);

        $assignableRoles = auth()->user()->getAssignableRoles();
        if (!in_array($request->role, $assignableRoles)) {
            return back()->with('error', 'You are not authorized to assign this role.');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        $user->assignRole($request->role);

        // Redirect to appropriate route based on request
        if (request()->is('admin/*')) {
            return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
        }

        return redirect()->route('user.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $this->authorize('edit-users');

        if (!auth()->user()->canAssignRoleTo($user)) {
            return back()->with('error', 'You are not authorized to edit this user.');
        }

        $assignableRoles = auth()->user()->getAssignableRoles();
        $roles = Role::whereIn('name', $assignableRoles)->get();

        // Return admin view if accessed from admin routes
        if (request()->is('admin/*')) {
            return view('frontend.admin.users.edit', compact('user', 'roles'));
        }

        return view('frontend.user.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('edit-users');

        if (!auth()->user()->canAssignRoleTo($user)) {
            return back()->with('error', 'You are not authorized to edit this user.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|exists:roles,name',
        ]);

        $assignableRoles = auth()->user()->getAssignableRoles();
        if (!in_array($request->role, $assignableRoles)) {
            return back()->with('error', 'You are not authorized to assign this role.');
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->syncRoles($request->role);

        // Redirect to appropriate route based on request
        if (request()->is('admin/*')) {
            return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
        }

        return redirect()->route('user.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $this->authorize('delete-users');

        if (!auth()->user()->canAssignRoleTo($user)) {
            return back()->with('error', 'You are not authorized to delete this user.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        // Redirect to appropriate route based on request
        if (request()->is('admin/*')) {
            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
        }

        return redirect()->route('user.users.index')->with('success', 'User deleted successfully.');
    }

    public function assignRole(Request $request, User $user)
    {
        $this->authorize('assign-roles-to-users');

        if (!auth()->user()->canAssignRoleTo($user)) {
            return back()->with('error', 'You are not authorized to assign roles to this user.');
        }

        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        $assignableRoles = auth()->user()->getAssignableRoles();
        if (!in_array($request->role, $assignableRoles)) {
            return back()->with('error', 'You are not authorized to assign this role.');
        }

        $user->syncRoles($request->role);

        return back()->with('success', 'Role assigned successfully.');
    }

    // Admin Management Methods (Super Admin only)
    public function adminsIndex()
    {
        $this->authorize('view-admins');

        $admins = User::role('admin')->with('roles')->get();
        $assignableRoles = ['admin'];

        return view('frontend.admin.admins.index', compact('admins', 'assignableRoles'));
    }

    public function adminsCreate()
    {
        $this->authorize('create-admins');

        $roles = Role::where('name', 'admin')->get();

        return view('frontend.admin.admins.create', compact('roles'));
    }

    public function adminsStore(Request $request)
    {
        $this->authorize('create-admins');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        $user->assignRole('admin');

        return redirect()->route('admin.admins.index')->with('success', 'Admin created successfully.');
    }

    public function adminsEdit(User $user)
    {
        $this->authorize('edit-admins');

        if (!$user->hasRole('admin')) {
            return back()->with('error', 'This user is not an admin.');
        }

        $roles = Role::where('name', 'admin')->get();

        return view('frontend.admin.admins.edit', compact('user', 'roles'));
    }

    public function adminsUpdate(Request $request, User $user)
    {
        $this->authorize('edit-admins');

        if (!$user->hasRole('admin')) {
            return back()->with('error', 'This user is not an admin.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.admins.index')->with('success', 'Admin updated successfully.');
    }

    public function adminsDestroy(User $user)
    {
        $this->authorize('delete-admins');

        if (!$user->hasRole('admin')) {
            return back()->with('error', 'This user is not an admin.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.admins.index')->with('success', 'Admin deleted successfully.');
    }
}
