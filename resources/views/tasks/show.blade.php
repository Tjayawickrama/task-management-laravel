@extends('layouts.app')

@section('title', $task->title)

@section('content')
<div class="mx-auto max-w-3xl">
    <a href="{{ route('tasks.index') }}" class="mb-4 inline-block text-sm text-slate-500 hover:text-slate-800">&larr; Back to tasks</a>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ $task->title }}</h1>
            <div class="flex-none">
                <x-status-badge :status="$task->status" />
            </div>
        </div>

        <div class="mt-5 rounded-lg bg-slate-50 p-4 text-sm leading-relaxed text-slate-700">
            @if ($task->description)
                <p class="whitespace-pre-line">{{ $task->description }}</p>
            @else
                <p class="text-slate-400">No description provided.</p>
            @endif
        </div>

        <dl class="mt-6 grid gap-4 text-sm sm:grid-cols-3">
            <div>
                <dt class="text-slate-500">Due date</dt>
                <dd class="mt-0.5 font-medium {{ $task->isOverdue() ? 'text-red-600' : 'text-slate-800' }}">
                    {{ $task->due_date?->format('M d, Y') ?? '—' }}
                    @if ($task->isOverdue()) <span class="text-xs">(Overdue)</span> @endif
                </dd>
            </div>
            <div>
                <dt class="text-slate-500">Created</dt>
                <dd class="mt-0.5 font-medium text-slate-800" title="{{ $task->created_at->format('M d, Y H:i') }}">
                    {{ $task->created_at->diffForHumans() }}
                </dd>
            </div>
            <div>
                <dt class="text-slate-500">Last updated</dt>
                <dd class="mt-0.5 font-medium text-slate-800" title="{{ $task->updated_at->format('M d, Y H:i') }}">
                    {{ $task->updated_at->diffForHumans() }}
                </dd>
            </div>
        </dl>

        {{-- Quick status change --}}
        <div class="mt-8 border-t border-slate-100 pt-6">
            <p class="mb-2 text-sm font-medium text-slate-700">Change status</p>
            <form method="POST" action="{{ route('tasks.status', $task) }}"
                  class="inline-flex overflow-hidden rounded-lg border border-slate-300">
                @csrf
                @method('PATCH')
                @foreach ($statuses as $status)
                    <button type="submit" name="status" value="{{ $status->value }}"
                            class="px-4 py-2 text-sm font-medium transition
                                   {{ $task->status === $status ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 hover:bg-slate-50' }}
                                   {{ ! $loop->first ? 'border-l border-slate-300' : '' }}">
                        {{ $status->label() }}
                    </button>
                @endforeach
            </form>
        </div>

        {{-- Actions --}}
        <div class="mt-8 flex flex-wrap gap-2 border-t border-slate-100 pt-6">
            <a href="{{ route('tasks.edit', $task) }}"
               class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500">Edit task</a>
            <button type="button"
                    data-delete-url="{{ route('tasks.destroy', $task) }}"
                    data-delete-title="{{ $task->title }}"
                    class="rounded-lg border border-red-200 px-4 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50">
                Delete
            </button>
        </div>
    </div>
</div>
@endsection