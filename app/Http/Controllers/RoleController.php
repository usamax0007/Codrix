<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $this->authorize('view-roles');

        $roles = Role::with('permissions')->get();
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode(' ', str_replace('-', ' ', $permission->name))[0];
        });

        return view('frontend.admin.roles.index', compact('roles', 'permissions'));
    }

    public function create()
    {
        $this->authorize('create-roles');

        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode(' ', str_replace('-', ' ', $permission->name))[0];
        });

        return view('frontend.admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $this->authorize('create-roles');

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

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $this->authorize('edit-roles');

        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode(' ', str_replace('-', ' ', $permission->name))[0];
        });

        $role->load('permissions');

        return view('frontend.admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $this->authorize('edit-roles');

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

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $this->authorize('delete-roles');

        // Prevent deletion of default roles
        if (in_array($role->name, ['super-admin', 'admin', 'user'])) {
            return back()->with('error', 'Cannot delete default roles.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }
}
