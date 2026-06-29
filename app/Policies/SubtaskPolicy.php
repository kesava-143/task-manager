<?php

namespace App\Policies;

use App\Models\Subtask;
use App\Models\User;

class SubtaskPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Subtask $subtask): bool
    {
        return $user->id === $subtask->task->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function destroy(User $user, Subtask $subtask): bool
    {
        return $user->id === $subtask->task->user_id;
    }
}