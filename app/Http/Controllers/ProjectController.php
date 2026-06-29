<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(['name' => 'required|string|max:255']);
        $request->user()->projects()->create($validated);
        return redirect(route('tasks.index'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        $validated = $request->validate(['name' => 'required|string|max:255']);

        $project->update($validated);

        return redirect(route('tasks.index'));
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->authorize('destroy', $project);
        $project->delete();
        return redirect(route('tasks.index'));
    }
}