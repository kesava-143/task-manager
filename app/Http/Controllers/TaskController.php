<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $query = $user->tasks()->with(['project', 'subtasks']); // Eager load relationships

        // Apply search filter if present
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Apply project filter if present
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Apply priority filter if present
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        return view('tasks.index', [
            'tasks' => $query->latest()->get(),
            'projects' => $user->projects()->latest()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'priority' => ['required', Rule::in(['Low', 'Medium', 'High'])],
            'deadline' => 'nullable|date',
        ]);
        $request->user()->tasks()->create($validated);
        return redirect(route('tasks.index'));
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $this->authorize('update', $task);
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'is_completed' => 'sometimes|required|boolean',
            'priority' => ['sometimes', 'required', Rule::in(['Low', 'Medium', 'High'])],
            'deadline' => 'nullable|date',
        ]);
        $task->update($validated);
        return redirect(route('tasks.index'));
    }

    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);
        $task->delete();
        return redirect(route('tasks.index'));
    }
}