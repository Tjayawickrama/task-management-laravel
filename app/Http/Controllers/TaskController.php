<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Enums\TaskStatus;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function index(Request $request)
{
    $tasks = Task::search($request->query('search'))
        ->status($request->query('status'))
        ->latest()
        ->paginate(10)
        ->withQueryString();

    $counts = DB::table('tasks')
        ->select('status', DB::raw('COUNT(*) as total'))
        ->groupBy('status')
        ->pluck('total', 'status');

    $stats = [
        'total'       => $counts->sum(),
        'pending'     => $counts['pending'] ?? 0,
        'in_progress' => $counts['in_progress'] ?? 0,
        'completed'   => $counts['completed'] ?? 0,
    ];

    return view('tasks.index', [
        'tasks'    => $tasks,
        'statuses' => TaskStatus::cases(),
        'stats'    => $stats,
    ]);
}

    public function create()
    {
        return view('tasks.create', ['statuses' => TaskStatus::cases()]);
    }

    public function store(TaskRequest $request)
    {
        Task::create($request->validated());

        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        return view('tasks.show', [
            'task'     => $task,
            'statuses' => TaskStatus::cases(),
        ]);
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', [
            'task'     => $task,
            'statuses' => TaskStatus::cases(),
        ]);
    }

    public function update(TaskRequest $request, Task $task)
{
    $task->update($request->validated());

    return redirect()->route('tasks.index')
        ->with('success', 'Task updated successfully.');
}

    public function updateStatus(Request $request, Task $task)
    {
        $data = $request->validate([
            'status' => ['required', Rule::enum(TaskStatus::class)],
        ]);

        $task->update($data);

        return back()->with('success', 'Task status updated.');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }
}