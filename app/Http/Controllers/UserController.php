<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // List users
    public function index()
    {
        if (!auth()->user()->hasRole('super_admin')) {
            return response()->view('errors.unauthorized', [], 403);
        }

        $users = User::with('roles')->get();
        return view('users.index', compact('users'));
    }

    // Show create form
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    // Store user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role_id' => 'required'
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // attach role
        $user->roles()->attach($request->role_id);

        return redirect('/users');
    }

    // Edit form
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();

        return view('users.edit', compact('user', 'roles'));
    }

    // Update
    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email,' . $id,
        'role_id' => 'required',
        'password' => 'nullable|min:6'
    ]);

    $user = User::findOrFail($id);

    $data = [
        'name' => $request->name,
        'email' => $request->email,
    ];

    // Only update password if provided
    if ($request->filled('password')) {
        $data['password'] = \Hash::make($request->password);
    }

    $user->update($data);

    // update role
    $user->roles()->sync([$request->role_id]);

    return redirect('/users');
}

    // Delete
    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect('/users');
    }
}