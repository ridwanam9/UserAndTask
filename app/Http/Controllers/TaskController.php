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

    public function update(Request $request, $id){
        $task = Task::findOrFail($id);
        $user = auth()->user();

        // Hanya admin atau pembuat task yang bisa edit
        if ($user->role !== 'admin' && $task->created_by !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'assigned_to' => 'required|uuid|exists:users,id',
            'status' => ['required', Rule::in(['pending', 'in_progress', 'done'])],
            'due_date' => 'required|date|after_or_equal:today',
        ]);

        // Validasi tambahan jika manager
        if ($user->role === 'manager') {
            $assignee = \App\Models\User::find($request->assigned_to);
            if ($assignee->role !== 'staff') {
                return response()->json(['message' => 'Manager only allowed to assign task to staff'], 403);
            }
        }

        $task->update($request->only(['title', 'description', 'assigned_to', 'status', 'due_date']));

        return response()->json(['message' => 'Task updated', 'task' => $task]);
    }

    public function destroy($id){
        $task = Task::findOrFail($id);
        $user = auth()->user();

        if ($user->role !== 'admin' && $task->created_by !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $task->delete();

        return response()->json(['message' => 'Task deleted']);
    }


}
