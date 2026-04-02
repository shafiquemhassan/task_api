<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        return auth()->user()->tasks()->latest()->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'priority' => 'in:low,medium,high',
            'status' => 'in:pending,in_progress,completed',
        ]);

        $task = Task::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'priority' => $request->priority ?? 'medium',
            'status' => $request->status ?? 'pending',
        ]);

        return response()->json($task, 201);
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        return $task;
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $task->update($request->only([
            'title',
            'description',
            'due_date',
            'priority',
            'status'
        ]));

        return response()->json($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }

    public function filter(Request $request)
{
    $query = auth()->user()->tasks()->latest();

    if ($request->status) {
        $query->where('status', $request->status);
    }

    if ($request->priority) {
        $query->where('priority', $request->priority);
    }

    if ($request->due_before) {
        $query->whereDate('due_date', '<=', $request->due_before);
    }

    if ($request->due_after) {
        $query->whereDate('due_date', '>=', $request->due_after);
    }

    return $query->get();
}

}
