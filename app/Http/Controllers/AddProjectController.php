<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;

class AddProjectController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasPermissionTo('view-projects')) {
            abort(403, 'You do not have permission to view projects.');
        }

        $projects = Project::latest()->get();
        $isAdmin = request()->routeIs('admin.projects.*');
        $view = $isAdmin ? 'frontend.admin.projects.index' : 'frontend.user.add-project.index';
        return view($view, compact('projects'));
    }

    public function create()
    {
        if (!auth()->user()->hasPermissionTo('create-projects')) {
            abort(403, 'You do not have permission to create projects.');
        }

        $isAdmin = request()->routeIs('admin.projects.*');
        $view = $isAdmin ? 'frontend.admin.projects.create' : 'frontend.user.add-project.create';
        return view($view);
    }

    public function store(ProjectRequest $request)
    {
        if (!auth()->user()->hasPermissionTo('create-projects')) {
            abort(403, 'You do not have permission to create projects.');
        }

        $project = Project::create($request->validated());
        
        $isAdmin = request()->routeIs('admin.projects.*');
        if ($isAdmin) {
            return response()->json([
                'success' => true,
                'message' => 'Project created successfully.',
                'project' => $project,
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Project created successfully.',
            'project' => $project,
        ]);
    }

    public function show(Project $project)
    {
        if (!auth()->user()->hasPermissionTo('view-projects')) {
            abort(403, 'You do not have permission to view projects.');
        }

        $isAdmin = request()->routeIs('admin.projects.*');
        $view = $isAdmin ? 'frontend.admin.projects.show' : 'frontend.user.add-project.show';
        return view($view, compact('project'));
    }

    public function edit(Project $project)
    {
        if (!auth()->user()->hasPermissionTo('edit-projects')) {
            abort(403, 'You do not have permission to edit projects.');
        }

        $isAdmin = request()->routeIs('admin.projects.*');
        $view = $isAdmin ? 'frontend.admin.projects.edit' : 'frontend.user.add-project.edit';
        return view($view, compact('project'));
    }

    public function update(ProjectRequest $request, Project $project)
    {
        if (!auth()->user()->hasPermissionTo('edit-projects')) {
            abort(403, 'You do not have permission to edit projects.');
        }

        $project->update($request->validated());
        
        $isAdmin = request()->routeIs('admin.projects.*');
        $route = $isAdmin ? 'admin.projects.index' : 'user.add-project.index';
        return redirect()->route($route)->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        if (!auth()->user()->hasPermissionTo('delete-projects')) {
            abort(403, 'You do not have permission to delete projects.');
        }

        $project->delete();
        
        $isAdmin = request()->routeIs('admin.projects.*');
        $route = $isAdmin ? 'admin.projects.index' : 'user.add-project.index';
        return redirect()->route($route)->with('success', 'Project deleted successfully.');
    }
}
