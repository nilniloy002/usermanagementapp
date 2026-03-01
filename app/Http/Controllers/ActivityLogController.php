<?php

namespace Vanguard\Http\Controllers;

use Vanguard\ActivityLog;
use Vanguard\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of activity logs.
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('causer')->latest();

        // Filter by log name
        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->user_id);
        }

        // Search in description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('description', 'like', "%{$search}%");
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(50);
        
        // Get users for filter dropdown
        $users = User::orderBy('first_name')->get();

        return view('activity-logs.index', compact('logs', 'users'));
    }

    /**
     * Display the specified log.
     */
    public function show($id)
    {
        $log = ActivityLog::with('causer', 'subject')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'log' => $log,
            'properties' => $log->properties
        ]);
    }

    /**
     * Clear old logs (optional - can be run via command)
     */
    public function clearOldLogs()
    {
        $days = 30; // Keep logs for 30 days
        $cutoff = now()->subDays($days);
        
        $deleted = ActivityLog::where('created_at', '<', $cutoff)->delete();
        
        return response()->json([
            'success' => true,
            'message' => "Deleted {$deleted} old logs"
        ]);
    }
}