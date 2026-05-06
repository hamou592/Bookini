<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Provider;
use Illuminate\Support\Facades\Storage;
class UserController extends Controller
{
    // List users
  public function index(Request $request)
{
    $query = User::with(['provider', 'roles']);

    // 🔍 Search
    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('email', 'like', '%' . $request->search . '%')
              ->orWhereHas('provider', function ($q2) use ($request) {
                  $q2->where('name', 'like', '%' . $request->search . '%');
              });
        });
    }

    // 🎯 Role filter
    if ($request->role) {
        $query->whereHas('roles', function ($q) use ($request) {
            $q->where('name', $request->role);
        });
    }

    $users = $query->paginate(10)->withQueryString();

    return view('users.index', compact('users'));
}

    // Show create form
    public function create()
    {
        $roles = Role::all();
        $providers = Provider::select('id','name')->get();
        return view('users.create', compact('roles','providers'));
    }

    // Store user

public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'role_id' => 'required',
        'new_provider_logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $providerId = null;
    $logoPath = null;

    // Upload logo
    if ($request->hasFile('new_provider_logo')) {
        $logoPath = $request->file('new_provider_logo')
            ->store('providers', 'public');
    }

    // CREATE NEW PROVIDER
    if ($request->provider_mode === 'new' && $request->new_provider_name) {

        $startDate = $request->new_provider_subscription_start_at
            ? Carbon::parse($request->new_provider_subscription_start_at)
            : now();

        $provider = Provider::create([
            'name' => $request->new_provider_name,
            'type' => $request->new_provider_type,
            'phone' => $request->new_provider_phone,
            'logo' => $logoPath,
            'subscription_status' => $request->new_provider_subscription_status,
            'subscription_start_at' => $startDate,
            'subscription_expires_at' => $startDate->copy()->addDays(30),
        ]);

        $providerId = $provider->id;
    } else {
        $providerId = $request->provider_id;
    }

    // CREATE USER
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'provider_id' => $providerId
    ]);

    $user->roles()->attach($request->role_id);

    return redirect('/users');
}

    // Edit form
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
    $providers = Provider::select('id', 'name')->get();
        return view('users.edit', compact('user', 'roles', 'providers'));
    }
//update


public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email,' . $id,
        'role_id' => 'required',
        'password' => 'nullable|min:6',
        'new_provider_logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $user = User::findOrFail($id);
    $provider = $user->provider;

    $providerId = $user->provider_id;

    // =========================
    // CASE: CREATE NEW PROVIDER
    // =========================
    if ($request->provider_mode === 'new' && $request->new_provider_name) {

        $logoPath = null;

        if ($request->hasFile('new_provider_logo')) {
            $logoPath = $request->file('new_provider_logo')
                ->store('providers', 'public');
        }

        $startDate = $request->new_provider_subscription_start_at
            ? Carbon::parse($request->new_provider_subscription_start_at)
            : now();

        $provider = Provider::create([
            'name' => $request->new_provider_name,
            'type' => $request->new_provider_type,
            'phone' => $request->new_provider_phone,
            'logo' => $logoPath,
            'subscription_status' => $request->new_provider_subscription_status,
            'subscription_start_at' => $startDate,
            'subscription_expires_at' => $startDate->copy()->addDays(30),
        ]);

        $providerId = $provider->id;
    }

    // =========================
    // CASE: EXISTING PROVIDER
    // =========================
    else {
        $providerId = $request->provider_id;
    }

    // =========================
    // UPDATE USER
    // =========================
    $data = [
        'name' => $request->name,
        'email' => $request->email,
        'provider_id' => $providerId
    ];

    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

    $user->update($data);

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