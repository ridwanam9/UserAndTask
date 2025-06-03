<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    // GET /users
    public function index()
    {
        $this->authorize('viewAny', User::class);
        return response()->json(User::all());
    }

    // POST /users
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => ['required', Rules\Password::defaults()],
            'role' => 'required|in:admin,manager,staff',
            'status' => 'boolean',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $request->status ?? true,
        ]);

        return response()->json(['message' => 'User created', 'user' => $user], 201);
    }
}
