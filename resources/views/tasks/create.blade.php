@extends('layouts.app')

@section('title', 'Create Task')

@section('content')
<div class="mx-auto max-w-2xl">
    <a href="{{ route('tasks.index') }}" class="mb-4 inline-block text-sm text-slate-500 hover:text-slate-800">&larr; Back to tasks</a>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <h1 class="text-xl font-bold text-slate-900">Create a new task</h1>
        <p class="mb-6 mt-1 text-sm text-slate-500">Fill in the details below.</p>

        <form method="POST" action="{{ route('tasks.store') }}">
            @csrf
            @include('tasks._form')

            <div class="mt-8 flex justify-end gap-2 border-t border-slate-100 pt-5">
                <a href="{{ route('tasks.index') }}"
                   class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</a>
                <button class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500">
                    Create task
                </button>
            </div>
        </form>
    </div>
</div>
@endsection