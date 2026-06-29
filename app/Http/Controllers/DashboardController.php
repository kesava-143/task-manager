<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the user's dashboard.
     */
    public function index(): View
    {
        $user = Auth::user();

        // Calculate stats
        $stats = [
            'total_projects' => $user->projects()->count(),
            'open_tasks' => $user->tasks()->where('is_completed', false)->count(),
            'completed_tasks' => $user->tasks()->where('is_completed', true)->count(),
        ];
        
        // Base query for open tasks with deadlines
        $tasksWithDeadlines = $user->tasks()
                                    ->with('project')
                                    ->where('is_completed', false)
                                    ->whereNotNull('deadline');

        // Clone the query to get tasks due soon
        $tasksDueSoon = (clone $tasksWithDeadlines)
                            ->whereBetween('deadline', [now()->startOfDay(), now()->addDays(7)->endOfDay()])
                            ->get();
        
        // Clone the query again to get overdue tasks
        $overdueTasks = (clone $tasksWithDeadlines)
                            ->where('deadline', '<', now()->startOfDay())
                            ->get();


        return view('dashboard', [
            'stats' => $stats,
            'tasksDueSoon' => $tasksDueSoon,
            'overdueTasks' => $overdueTasks,
        ]);
    }
}