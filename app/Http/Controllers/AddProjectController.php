<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;

class AddProjectController extends Controller
{
    public function index()
    {
        $projects = Project::latest()->get();
        return view('frontend.user.add-project.index', compact('projects'));
    }

    public function create()
    {
        return view('frontend.user.add-project.create');
    }

    public function store(ProjectRequest $request)
    {
        Project::create($request->validated());
        return redirect()->route('user.add-project.index')->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        return view('frontend.user.add-project.show', compact('project'));
    }

    public function edit(Project $project)
    {
        return view('frontend.user.add-project.edit', compact('project'));
    }

    public function update(ProjectRequest $request, Project $project)
    {
        $project->update($request->validated());
        return redirect()->route('user.add-project.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('user.add-project.index')->with('success', 'Project deleted successfully.');
    }
}
