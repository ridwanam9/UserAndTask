<?php
namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    // GET /tasks
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return Task::all();
        }

        // Manager atau Staff hanya melihat task yang dibuat/diberikan kepadanya
        return Task::where('created_by', $user->id)
                    ->orWhere('assigned_to', $user->id)
                    ->get();
    }

    // POST /tasks
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'assigned_to' => 'required|uuid|exists:users,id',
            'status' => ['required', Rule::in(['pending', 'in_progress', 'done'])],
            'due_date' => 'required|date|after:today',
        ]);

        // Cek assignment valid sesuai role
        if ($user->role === 'manager') {
            $assignee = \App\Models\User::find($request->assigned_to);
            if (!$assignee || $assignee->role !== 'staff') {
                return response()->json(['message' => 'Manager only allowed to assign task to staff'], 403);
            }
        }

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'status' => $request->status,
            'due_date' => $request->due_date,
            'created_by' => $user->id,
        ]);

        return response()->json(['message' => 'Task created', 'task' => $task], 201);
    }
}
