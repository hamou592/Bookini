<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function index()
    {
        $providers = Provider::withCount('users')->paginate(10);
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
        Provider::findOrFail($id)->delete();
        return redirect('/providers');
    }
}