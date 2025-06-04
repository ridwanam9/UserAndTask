<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $logs = ActivityLog::with('user:id,name,email,role')->orderBy('logged_at', 'desc')->get();

        return response()->json($logs);
    }
}

