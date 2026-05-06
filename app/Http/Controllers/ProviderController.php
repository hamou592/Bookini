<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{

public function index(Request $request)
{
    $query = Provider::query()->withCount('users');
    // 🔍 Search (name OR phone)
    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('phone', 'like', '%' . $request->search . '%');
        });
    }

    // 🎯 Filter: type
    if ($request->type) {
        $query->where('type', $request->type);
    }

    // 🎯 Filter: status
    if ($request->status) {
        $query->where('subscription_status', $request->status);
    }

    $providers = $query->latest()->paginate(10)->withQueryString();

    // ⚡ AJAX response
    if ($request->ajax()) {
        return view('providers.partials.table', compact('providers'))->render();
    }

    return view('providers.index', compact('providers'));
}

    public function create()
    {
        return view('providers.create');
    }


public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'type' => 'required|in:clinic,doctor',
        'phone' => 'nullable',
        'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $logoPath = null;

    if ($request->hasFile('logo')) {
        $logoPath = $request->file('logo')->store('providers', 'public');
    }

    $startDate = $request->subscription_start_at
        ? Carbon::parse($request->subscription_start_at)
        : now();

    Provider::create([
        'name' => $request->name,
        'type' => $request->type,
        'phone' => $request->phone,
        'logo' => $logoPath,
        'subscription_status' => $request->subscription_status,
        'subscription_start_at' => $startDate,
        'subscription_expires_at' => $startDate->copy()->addDays(30),
    ]);

    return redirect('/providers');
}

    public function edit($id)
    {
        $provider = Provider::findOrFail($id);
        return view('providers.edit', compact('provider'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required',
        'type' => 'required|in:clinic,doctor',
        'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $provider = Provider::findOrFail($id);

    $logoPath = $provider->logo;

    if ($request->hasFile('logo')) {
        $logoPath = $request->file('logo')->store('providers', 'public');
    }

    $startDate = $request->subscription_start_at
        ? Carbon::parse($request->subscription_start_at)
        : $provider->subscription_start_at;

    $provider->update([
        'name' => $request->name,
        'type' => $request->type,
        'phone' => $request->phone,
        'logo' => $logoPath,
        'subscription_status' => $request->subscription_status,
        'subscription_start_at' => $startDate,
        'subscription_expires_at' => $startDate ? $startDate->copy()->addDays(30) : null,
    ]);

    return redirect('/providers');
}

    public function destroy($id)
{
    $provider = Provider::findOrFail($id);

    // detach users manually (extra safety)
    $provider->users()->update([
        'provider_id' => null
    ]);

    $provider->delete();

    return redirect('/providers');
}
}