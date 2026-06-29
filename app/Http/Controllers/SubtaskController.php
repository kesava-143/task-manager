<?php

namespace App\Http\Controllers;

use App\Models\Subtask;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SubtaskController extends Controller
{
    public function store(Request $request, Task $task): RedirectResponse
    {
        // This checks that the user is authorized to update the parent task
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $task->subtasks()->create($validated);

        return redirect(route('tasks.index'));
    }

    public function update(Request $request, Subtask $subtask): RedirectResponse
    {
        $this->authorize('update', $subtask);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'is_completed' => 'sometimes|required|boolean',
        ]);

        $subtask->update($validated);

        return redirect(route('tasks.index'));
    }

    public function destroy(Subtask $subtask): RedirectResponse
    {
        $this->authorize('destroy', $subtask);
        
        $subtask->delete();

        return redirect(route('tasks.index'));
    }
}