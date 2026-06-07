<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $action = $request->input('action', '');

        $logs = ActivityLog::with('user')
            ->when($action, fn($q) => $q->where('action', $action))
            ->latest()
            ->paginate(20);

        $actions = ActivityLog::select('action')->distinct()->pluck('action');

        return view('activity_logs.index', compact('logs', 'actions', 'action'));
    }
}
