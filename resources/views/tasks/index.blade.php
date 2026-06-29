<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Task Management System') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="md:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h3 class="text-lg font-medium mb-4">Filter & Search</h3>
                            <form method="GET" action="{{ route('tasks.index') }}">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div>
                                        <x-input-label for="search" :value="__('Search by Name')" />
                                        <x-text-input id="search" class="block mt-1 w-full" type="text" name="search" :value="request('search')" />
                                    </div>
                                    <div>
                                        <x-input-label for="filter_project_id" :value="__('Project')" />
                                        <select name="project_id" id="filter_project_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                            <option value="">All Projects</option>
                                            @foreach ($projects as $project)
                                                <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <x-input-label for="filter_priority" :value="__('Priority')" />
                                        <select name="priority" id="filter_priority" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                            <option value="">All Priorities</option>
                                            <option value="Low" {{ request('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                                            <option value="Medium" {{ request('priority') == 'Medium' ? 'selected' : '' }}>Medium</option>
                                            <option value="High" {{ request('priority') == 'High' ? 'selected' : '' }}>High</option>
                                        </select>
                                    </div>
                                    <div class="flex items-end space-x-2">
                                        <x-primary-button class="w-full justify-center">Filter</x-primary-button>
                                        <a href="{{ route('tasks.index') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-200 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-500">Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h3 class="text-lg font-medium mb-4">Add a New Task</h3>
                             <form method="POST" action="{{ route('tasks.store') }}" class="mb-6 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                @csrf
                                <div class="mb-4">
                                    <x-input-label for="title" :value="__('Task Name')" />
                                    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div>
                                        <x-input-label for="project_id" :value="__('Project')" />
                                        <select name="project_id" id="project_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                            <option value="">-- Select --</option>
                                            @foreach ($projects as $project)
                                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <x-input-label for="priority" :value="__('Priority')" />
                                        <select name="priority" id="priority" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                            <option value="Low">Low</option>
                                            <option value="Medium" selected>Medium</option>
                                            <option value="High">High</option>
                                        </select>
                                    </div>
                                    <div>
                                        <x-input-label for="deadline" :value="__('Deadline')" />
                                        <x-text-input id="deadline" class="block mt-1 w-full" type="date" name="deadline" />
                                    </div>
                                </div>
                                <x-primary-button>
                                    {{ __('+ Add New Task') }}
                                </x-primary-button>
                            </form>

                            <h3 class="text-lg font-medium mb-4 mt-6">Your Tasks</h3>
                            <div class="mt-6 space-y-4">
                                @php
                                    $priority_colors = [
                                        'Low' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                        'Medium' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                        'High' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                    ];
                                @endphp
                                @forelse ($tasks as $task)
                                    <div x-data="{ editing: false, showSubtasks: false }" class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                        <div x-show="!editing" class="flex items-start justify-between">
                                            <div>
                                                <span class="{{ $task->is_completed ? 'line-through text-gray-500' : '' }}">{{ $task->title }}</span>
                                                <div class="flex items-center flex-wrap gap-x-3 text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    <span>Project: {{ $task->project->name }}</span>
                                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $priority_colors[$task->priority] ?? '' }}">{{ $task->priority }} Priority</span>
                                                    @if($task->deadline)
                                                        <span>Deadline: {{ \Carbon\Carbon::parse($task->deadline)->format('M d, Y') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2 flex-shrink-0">
                                                <button @click="showSubtasks = !showSubtasks" class="text-gray-400 hover:text-purple-500" title="Show/Hide Subtasks"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg></button>
                                                <button @click="editing = true" class="text-gray-400 hover:text-blue-500" title="Edit Task"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg></button>
                                                <form method="POST" action="{{ route('tasks.update', $task) }}"> @csrf @method('PATCH') <input type="hidden" name="is_completed" value="{{ $task->is_completed ? 0 : 1 }}"> <button type="submit" class="text-gray-400 hover:text-green-500" title="Mark as {{ $task->is_completed ? 'incomplete' : 'complete' }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></button></form>
                                                <form method="POST" action="{{ route('tasks.destroy', $task) }}"> @csrf @method('DELETE') <button type="submit" class="text-gray-400 hover:text-red-500" title="Delete Task" onclick="return confirm('Are you sure?')"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button></form>
                                            </div>
                                        </div>
                                        <div x-show="editing" x-cloak><form method="POST" action="{{ route('tasks.update', $task) }}"> @csrf @method('PATCH') <x-text-input class="block w-full" type="text" name="title" value="{{ $task->title }}" required /> <div class="mt-2 space-x-2"><x-primary-button> {{ __('Save') }} </x-primary-button> <button type="button" @click="editing = false" class="text-sm text-gray-600 dark:text-gray-400">Cancel</button></div></form></div>
                                        <div x-show="showSubtasks" x-cloak class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                                            <h4 class="text-sm font-semibold mb-2">Subtasks ({{ $task->subtasks->count() }})</h4>
                                            <form method="POST" action="{{ route('tasks.subtasks.store', $task) }}" class="flex items-center mb-3"> @csrf <x-text-input class="block w-full text-sm" type="text" name="title" placeholder="Add a new subtask..." required /> <x-primary-button class="ms-2 text-xs">Add</x-primary-button></form>
                                            <div class="space-y-2">
                                                @foreach ($task->subtasks as $subtask)
                                                    <div x-data="{ subtaskEditing: false }" class="text-sm">
                                                        <div x-show="!subtaskEditing" class="flex items-center justify-between"><span class="{{ $subtask->is_completed ? 'line-through text-gray-500' : '' }}">{{ $subtask->title }}</span><div class="flex items-center space-x-2"><button @click="subtaskEditing = true" class="text-gray-400 hover:text-blue-500" title="Edit Subtask"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg></button><form method="POST" action="{{ route('subtasks.update', $subtask) }}"> @csrf @method('PATCH') <input type="hidden" name="is_completed" value="{{ $subtask->is_completed ? 0 : 1 }}"> <button type="submit" class="text-gray-400 hover:text-green-500" title="Toggle Status"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></button></form><form method="POST" action="{{ route('subtasks.destroy', $subtask) }}"> @csrf @method('DELETE') <button type="submit" class="text-gray-400 hover:text-red-500" title="Delete Subtask"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button></form></div></div>
                                                        <div x-show="subtaskEditing" x-cloak><form method="POST" action="{{ route('subtasks.update', $subtask) }}"> @csrf @method('PATCH') <x-text-input class="block w-full text-sm" type="text" name="title" value="{{ $subtask->title }}" required /> <div class="mt-2 space-x-2"><x-primary-button class="text-xs">Save</x-primary-button> <button type="button" @click="subtaskEditing = false" class="text-sm text-gray-600 dark:text-gray-400">Cancel</button></div></form></div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-center text-gray-500 dark:text-gray-400">No tasks match the current filters.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium mb-4">Projects</h3>
                        <form method="POST" action="{{ route('projects.store') }}" class="mb-6">@csrf<div class="flex items-center"><x-text-input id="name" class="block mt-1 w-full" type="text" name="name" required placeholder="New project name..." /> <x-primary-button class="ms-3"> {{ __('Add') }} </x-primary-button></div></form>
                        <div class="mt-6 space-y-4">
                            @forelse ($projects as $project)
                                <div x-data="{ editing: false }" class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                    <div x-show="!editing" class="flex items-center justify-between">
                                        <span>{{ $project->name }}</span>
                                        <div class="flex items-center space-x-2">
                                            <button @click="editing = true" class="text-gray-400 hover:text-blue-500" title="Edit Project"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg></button>
                                            <form method="POST" action="{{ route('projects.destroy', $project) }}"> @csrf @method('DELETE') <button type="submit" class="text-gray-400 hover:text-red-500" title="Delete Project" onclick="return confirm('Are you sure? This will also delete all tasks in this project.')"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button></form>
                                        </div>
                                    </div>
                                    <div x-show="editing" x-cloak><form method="POST" action="{{ route('projects.update', $project) }}"> @csrf @method('PATCH') <x-text-input class="block w-full" type="text" name="name" value="{{ $project->name }}" required /> <div class="mt-2 space-x-2"><x-primary-button> {{ __('Save') }} </x-primary-button> <button type="button" @click="editing = false" class="text-sm text-gray-600 dark:text-gray-400">Cancel</button></div></form></div>
                                </div>
                            @empty
                                <p>You haven't created any projects yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>