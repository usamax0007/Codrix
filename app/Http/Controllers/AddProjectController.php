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
        
        if (request()->routeIs('admin.projects.*')) {
            return view('frontend.admin.projects.index', compact('projects'));
        } elseif (request()->routeIs('super-admin.projects.*')) {
            return view('frontend.super-admin.projects.index', compact('projects'));
        }
        
        return view('frontend.user.add-project.index', compact('projects'));
    }

    public function create()
    {
        if (!auth()->user()->hasPermissionTo('create-projects')) {
            abort(403, 'You do not have permission to create projects.');
        }

        if (request()->routeIs('admin.projects.*')) {
            return view('frontend.admin.projects.create');
        } elseif (request()->routeIs('super-admin.projects.*')) {
            return view('frontend.super-admin.projects.create');
        }
        
        return view('frontend.user.add-project.create');
    }

    public function store(ProjectRequest $request)
    {
        if (!auth()->user()->hasPermissionTo('create-projects')) {
            abort(403, 'You do not have permission to create projects.');
        }

        $project = Project::create($request->validated());
        
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

        if (request()->routeIs('admin.projects.*')) {
            return view('frontend.admin.projects.show', compact('project'));
        } elseif (request()->routeIs('super-admin.projects.*')) {
            return view('frontend.super-admin.projects.show', compact('project'));
        }
        
        return view('frontend.user.add-project.show', compact('project'));
    }

    public function edit(Project $project)
    {
        if (!auth()->user()->hasPermissionTo('edit-projects')) {
            abort(403, 'You do not have permission to edit projects.');
        }

        if (request()->routeIs('admin.projects.*')) {
            return view('frontend.admin.projects.edit', compact('project'));
        } elseif (request()->routeIs('super-admin.projects.*')) {
            return view('frontend.super-admin.projects.edit', compact('project'));
        }
        
        return view('frontend.user.add-project.edit', compact('project'));
    }

    public function update(ProjectRequest $request, Project $project)
    {
        if (!auth()->user()->hasPermissionTo('edit-projects')) {
            abort(403, 'You do not have permission to edit projects.');
        }

        $project->update($request->validated());
        
        if (request()->routeIs('admin.projects.*')) {
            return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully.');
        } elseif (request()->routeIs('super-admin.projects.*')) {
            return redirect()->route('super-admin.projects.index')->with('success', 'Project updated successfully.');
        }
        
        return redirect()->route('user.add-project.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        if (!auth()->user()->hasPermissionTo('delete-projects')) {
            abort(403, 'You do not have permission to delete projects.');
        }

        $project->delete();
        
        if (request()->routeIs('admin.projects.*')) {
            return redirect()->route('admin.projects.index')->with('success', 'Project deleted successfully.');
        } elseif (request()->routeIs('super-admin.projects.*')) {
            return redirect()->route('super-admin.projects.index')->with('success', 'Project deleted successfully.');
        }
        
        return redirect()->route('user.add-project.index')->with('success', 'Project deleted successfully.');
    }
}
