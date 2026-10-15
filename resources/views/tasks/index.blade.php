@extends('layouts.app')

@section('title', 'Tasks')

@section('content')

{{-- Heading --}}
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">My Tasks</h1>
        <p class="text-sm text-slate-500">Keep track of what needs to get done.</p>
    </div>
    <a href="{{ route('tasks.create') }}"
       class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        New Task
    </a>
</div>

{{-- Pet care banner --}}
<div class="relative mb-6 overflow-hidden rounded-2xl bg-gradient-to-r from-[#0e7c86] to-[#2aa6a0] p-6 text-white shadow-lg">
    <div class="relative z-10 max-w-[60%] sm:max-w-sm">
        <p class="text-xs font-medium uppercase tracking-widest text-teal-100">Don't forget</p>
        <h2 class="mt-1 text-xl font-bold leading-snug sm:text-2xl">Keep your furry friend happy!</h2>
        <p class="mt-1 text-sm text-teal-50">Feeding, walks, grooming and vet visits, all in one place.</p>
        <a href="{{ route('tasks.index', ['status' => 'pending']) }}"
           class="mt-4 inline-block rounded-lg bg-white px-4 py-2 text-xs font-semibold text-teal-800 shadow transition hover:bg-teal-50">
            See pending tasks
        </a>
    </div>

    <div class="absolute -right-4 -top-6 h-32 w-32 rounded-full bg-white/10"></div>
    <div class="absolute bottom-0 right-24 h-16 w-16 rounded-full bg-white/10"></div>
    <x-dog class="absolute -bottom-3 right-2 h-36 w-36 sm:right-8 sm:h-44 sm:w-44" />
</div>

{{-- Stat cards (click to filter) --}}
@php
    $cards = [
        ['label' => 'All tasks',   'value' => $stats['total'],       'status' => null],
        ['label' => 'Pending',     'value' => $stats['pending'],     'status' => 'pending'],
        ['label' => 'In Progress', 'value' => $stats['in_progress'], 'status' => 'in_progress'],
        ['label' => 'Completed',   'value' => $stats['completed'],   'status' => 'completed'],
    ];
@endphp

<div class="mb-6 grid grid-cols-2 gap-3 lg:grid-cols-4">
    @foreach ($cards as $card)
        @php $active = (request('status') ?? '') === ($card['status'] ?? ''); @endphp
        <a href="{{ route('tasks.index', array_filter(['status' => $card['status'], 'search' => request('search')])) }}"
   data-keep-scroll
   class="rounded-xl border bg-white p-4 shadow-sm transition hover:shadow-md
          {{ $active ? 'border-indigo-500 ring-1 ring-indigo-500' : 'border-slate-200' }}">
            <p class="text-sm text-slate-500">{{ $card['label'] }}</p>
            <p class="mt-1 text-2xl font-bold text-slate-900">{{ $card['value'] }}</p>
        </a>
    @endforeach
</div>

{{-- Search + filter --}}
<form method="GET" action="{{ route('tasks.index') }}" data-keep-scroll
      class="mb-4 flex flex-col gap-2 rounded-xl border border-slate-200 bg-white p-3 shadow-sm sm:flex-row">

    <div class="flex flex-1">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search tasks by title..."
               class="w-full rounded-l-lg border border-r-0 border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
        <button type="submit" title="Search" aria-label="Search"
                class="rounded-r-lg bg-indigo-600 px-3.5 text-white transition hover:bg-indigo-500">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </button>
    </div>

    <select name="status" onchange="this.form.requestSubmit()"
            class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
        <option value="">All statuses</option>
        @foreach ($statuses as $status)
            <option value="{{ $status->value }}" @selected(request('status') === $status->value)>
                {{ $status->label() }}
            </option>
        @endforeach
    </select>

    @if (request('search') || request('status'))
        <a href="{{ route('tasks.index') }}" data-keep-scroll
           class="rounded-lg border border-slate-300 px-4 py-2 text-center text-sm font-medium text-slate-600 hover:bg-slate-50">
            Reset
        </a>
    @endif
</form>

{{-- Table --}}
<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3 font-semibold">Task</th>
                    <th class="px-4 py-3 font-semibold">Status</th>
                    <th class="px-4 py-3 font-semibold">Due date</th>
                    <th class="px-4 py-3 font-semibold">Created</th>
                    <th class="px-4 py-3 text-right font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            @forelse ($tasks as $task)
                <tr class="transition hover:bg-slate-50">
                    <td class="max-w-xs px-4 py-3">
                        <a href="{{ route('tasks.show', $task) }}"
                           class="font-medium text-slate-900 hover:text-indigo-600">{{ $task->title }}</a>
                        @if ($task->description)
                            <p class="mt-0.5 truncate text-xs text-slate-500">{{ str($task->description)->limit(70) }}</p>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <x-status-badge :status="$task->status" />
                    </td>
                    <td class="whitespace-nowrap px-4 py-3">
                        @if ($task->due_date)
                            <span class="{{ $task->isOverdue() ? 'font-medium text-red-600' : 'text-slate-600' }}">
                                {{ $task->due_date->format('M d, Y') }}
                            </span>
                            @if ($task->isOverdue())
                                <span class="ml-1 rounded bg-red-50 px-1.5 py-0.5 text-xs font-medium text-red-600">Overdue</span>
                            @endif
                        @else
                            <span class="text-slate-400">—</span>
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-4 py-3 text-slate-500">{{ $task->created_at->format('M d, Y') }}</td>
                    <td class="whitespace-nowrap px-4 py-3">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('tasks.show', $task) }}" title="View"
                               class="rounded-md p-1.5 text-slate-500 hover:bg-slate-100 hover:text-slate-800">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12S6 5 12 5s9.5 7 9.5 7-3.5 7-9.5 7S2.5 12 2.5 12z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </a>
                            <a href="{{ route('tasks.edit', $task) }}" title="Edit"
                               class="rounded-md p-1.5 text-slate-500 hover:bg-indigo-50 hover:text-indigo-600">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.9 3.9a2.1 2.1 0 013 3L7.5 19.3 3 21l1.7-4.5L16.9 3.9z"/>
                                </svg>
                            </a>
                            <button type="button" title="Delete"
                                    data-delete-url="{{ route('tasks.destroy', $task) }}"
                                    data-delete-title="{{ $task->title }}"
                                    class="rounded-md p-1.5 text-slate-500 hover:bg-red-50 hover:text-red-600">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7V4h6v3m-8 0l1 13h8l1-13"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-14 text-center">
                        <p class="text-base font-semibold text-slate-700">No tasks found</p>
                        <p class="mt-1 text-sm text-slate-500">
                            @if (request()->hasAny(['search', 'status']))
                                Try a different search or filter.
                            @else
                                Create your first task to get started.
                            @endif
                        </p>
                        <a href="{{ route('tasks.create') }}"
                           class="mt-4 inline-block rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                            New Task
                        </a>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-5" data-keep-scroll>
    {{ $tasks->links() }}
</div>
@endsection