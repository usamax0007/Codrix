<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isSuperAdmin()) {
            $this->authorize('view-roles');
        }

        $roles = Role::with('permissions', 'users')->get();
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode(' ', str_replace('-', ' ', $permission->name))[0];
        });

        $view = request()->routeIs('super-admin.roles.*') 
            ? 'frontend.super-admin.roles.index' 
            : 'frontend.admin.roles.index';

        return view($view, compact('roles', 'permissions'));
    }

    public function create()
    {
        if (!auth()->user()->isSuperAdmin()) {
            $this->authorize('create-roles');
        }

        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode(' ', str_replace('-', ' ', $permission->name))[0];
        });

        $view = request()->routeIs('super-admin.roles.*') 
            ? 'frontend.super-admin.roles.create' 
            : 'frontend.admin.roles.create';

        return view($view, compact('permissions'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            $this->authorize('create-roles');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        $route = request()->routeIs('super-admin.roles.*') 
            ? 'super-admin.roles.index' 
            : 'admin.roles.index';

        return redirect()->route($route)->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        if (!auth()->user()->isSuperAdmin()) {
            $this->authorize('edit-roles');
        }

        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode(' ', str_replace('-', ' ', $permission->name))[0];
        });

        $role->load('permissions');

        $view = request()->routeIs('super-admin.roles.*') 
            ? 'frontend.super-admin.roles.edit' 
            : 'frontend.admin.roles.edit';

        return view($view, compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        if (!auth()->user()->isSuperAdmin()) {
            $this->authorize('edit-roles');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->update([
            'name' => $request->name,
        ]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        } else {
            $role->syncPermissions([]);
        }

        $route = request()->routeIs('super-admin.roles.*') 
            ? 'super-admin.roles.index' 
            : 'admin.roles.index';

        return redirect()->route($route)->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        if (!auth()->user()->isSuperAdmin()) {
            $this->authorize('delete-roles');
        }

        // Prevent deletion of default roles
        if (in_array($role->name, ['super-admin', 'admin', 'user'])) {
            return back()->with('error', 'Cannot delete default roles.');
        }

        $role->delete();

        $route = request()->routeIs('super-admin.roles.*') 
            ? 'super-admin.roles.index' 
            : 'admin.roles.index';

        return redirect()->route($route)->with('success', 'Role deleted successfully.');
    }
}
