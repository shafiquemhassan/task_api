<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class LogsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $logs = ActivityLog::latest()->get();
        } else {
            $logs = $user->activityLogs()->latest()->get();
        }

        return response()->json($logs);
    }
}
