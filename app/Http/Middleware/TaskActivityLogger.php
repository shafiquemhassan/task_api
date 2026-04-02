<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Symfony\Component\HttpFoundation\Response;

class TaskActivityLogger
{
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        $user = $request->user();
        if (!$user || !$response->isSuccessful()) {
            return;
        }

        $action = null;
        $taskId = null;

        if ($request->isMethod('post') && $request->routeIs('tasks.store')) {
            $data = json_decode($response->getContent(), true);
            if (isset($data['id'])) {
                $action = 'created';
                $taskId = $data['id'];
            }
        } elseif (($request->isMethod('put') || $request->isMethod('patch')) && $request->routeIs('tasks.update')) {
            $task = $request->route('task');
            if ($task) {
                $action = 'updated';
                $taskId = is_object($task) ? $task->id : $task;
            }
        } elseif ($request->isMethod('delete') && $request->routeIs('tasks.destroy')) {
            $task = $request->route('task');
            if ($task) {
                $action = 'deleted';
                $taskId = is_object($task) ? $task->id : $task;
            }
        }

        if ($action && $taskId) {
            ActivityLog::create([
                'user_id' => $user->id,
                'task_id' => $taskId,
                'action'  => $action,
            ]);
        }
    }
}

