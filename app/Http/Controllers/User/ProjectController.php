<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreProjectRequest;
use App\Http\Requests\User\UpdateProjectRequest;
use App\Models\Project;
use App\Services\Project\ProjectService;
use App\Support\AppPermission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function __construct(private readonly ProjectService $projects) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Project::class);

        return view('user.projects.index', [
            'projects' => $this->projects->listFor($request->user()),
            'canManage' => $request->user()->can(AppPermission::PROJECTS_MANAGE),
        ]);
    }

    public function show(Request $request, Project $project): View
    {
        $this->authorize('view', $project);

        $project = $this->projects->loadDetails($project, $request->user());

        return view('user.projects.show', [
            'project' => $project,
            'progress' => $project->progressStats(),
            'canManage' => $request->user()->can(AppPermission::PROJECTS_MANAGE),
        ]);
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $project = $this->projects->create($request->user(), $request->projectData());

        return redirect()
            ->route('user.projects.show', $project)
            ->with('success', 'Project created successfully.');
    }

    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $this->projects->update($project, $request->projectData());

        return redirect()
            ->route('user.projects.show', $project)
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(Request $request, Project $project): RedirectResponse
    {
        $this->authorize('delete', $project);

        $this->projects->delete($project);

        return redirect()
            ->route('user.projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}
