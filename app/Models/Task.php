<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; // Add this

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'is_completed',
        'user_id',
        'project_id',
        'priority',
        'deadline',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get all of the subtasks for the Task.
     */
    public function subtasks(): HasMany
    {
        return $this->hasMany(Subtask::class);
    }
}