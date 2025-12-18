<?php

namespace App\Http\Controllers;

use App\Models\LogActivity;
use Illuminate\Http\Request;

class LogActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = LogActivity::with('user')->latest();

        // Filtering
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('action')) {
            $query->where('action', $request->action);
        }

        if ($request->has('module')) {
            $query->where('module', 'LIKE', "%{$request->module}%");
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(50);

        return response()->json($logs);
    }

    public function show($id)
    {
        $log = LogActivity::with('user')->findOrFail($id);

        return response()->json($log);
    }

    public function getModules()
    {
        $modules = LogActivity::distinct()->pluck('module');

        return response()->json($modules);
    }

    public function getActions()
    {
        $actions = LogActivity::distinct()->pluck('action');

        return response()->json($actions);
    }
}
