<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Projects</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $stats['total_projects'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Open Tasks</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $stats['open_tasks'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Completed Tasks</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $stats['completed_tasks'] }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium mb-4">Tasks Due in Next 7 Days</h3>
                        <div class="space-y-3">
                            @forelse ($tasksDueSoon as $task)
                                <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                    <div class="flex justify-between items-center">
                                        <a href="{{ route('tasks.index') }}" class="font-semibold hover:underline">{{ $task->title }}</a>
                                        <span class="text-sm text-yellow-600 dark:text-yellow-400">{{ \Carbon\Carbon::parse($task->deadline)->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Project: {{ $task->project->name }}</p>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400">No tasks are due soon. Great job!</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium mb-4 text-red-600 dark:text-red-400">Overdue Tasks</h3>
                        <div class="space-y-3">
                             @forelse ($overdueTasks as $task)
                                <div class="p-3 bg-red-50 dark:bg-red-900/50 rounded-md">
                                    <div class="flex justify-between items-center">
                                        <a href="{{ route('tasks.index') }}" class="font-semibold hover:underline">{{ $task->title }}</a>
                                        <span class="text-sm text-red-600 dark:text-red-400">{{ \Carbon\Carbon::parse($task->deadline)->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Project: {{ $task->project->name }}</p>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400">No overdue tasks. Phew!</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>