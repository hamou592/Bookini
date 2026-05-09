<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Provider;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function index()
    {
        $query = Service::with(['provider', 'department']);

        // SUPER ADMIN => all services
        if (!Auth::user()->hasRole('super_admin')) {

            $query->where('provider_id', Auth::user()->provider_id);
        }

        $services = $query->latest('id')->get();

        return view('services.index', compact('services'));
    }

    public function create()
    {
        $user = Auth::user();

        // SUPER ADMIN
        if ($user->hasRole('super_admin')) {

            $providers = Provider::all();
            $departments = Department::all();

        } else {

            $providers = Provider::where('id', $user->provider_id)->get();

            $departments = Department::where(
                'provider_id',
                $user->provider_id
            )->get();
        }

        return view(
            'services.create',
            compact('providers', 'departments')
        );
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'provider_id' => 'required|exists:providers,id',
            'department_id' => 'nullable|exists:departments,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'duration' => 'required|numeric',
            'is_active' => 'required|boolean',
        ]);

        // SECURITY
        if (!$user->hasRole('super_admin')) {

            if ($request->provider_id != $user->provider_id) {
                abort(403);
            }
        }

        Service::create([
            'provider_id' => $request->provider_id,
            'department_id' => $request->department_id,
            'name' => $request->name,
            'price' => $request->price,
            'duration' => $request->duration,
            'is_active' => $request->is_active,
        ]);

        return redirect('/services')
            ->with('success', 'Service created successfully');
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);

        $user = Auth::user();

        // SECURITY
        if (
            !$user->hasRole('super_admin')
            && $service->provider_id != $user->provider_id
        ) {
            abort(403);
        }

        if ($user->hasRole('super_admin')) {

            $providers = Provider::all();

            $departments = Department::all();

        } else {

            $providers = Provider::where(
                'id',
                $user->provider_id
            )->get();

            $departments = Department::where(
                'provider_id',
                $user->provider_id
            )->get();
        }

        return view(
            'services.edit',
            compact(
                'service',
                'providers',
                'departments'
            )
        );
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $user = Auth::user();

        // SECURITY
        if (
            !$user->hasRole('super_admin')
            && $service->provider_id != $user->provider_id
        ) {
            abort(403);
        }

        $request->validate([
            'provider_id' => 'required|exists:providers,id',
            'department_id' => 'nullable|exists:departments,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'duration' => 'required|numeric',
            'is_active' => 'required|boolean',
        ]);

        $service->update([
            'provider_id' => $request->provider_id,
            'department_id' => $request->department_id,
            'name' => $request->name,
            'price' => $request->price,
            'duration' => $request->duration,
            'is_active' => $request->is_active,
        ]);

        return redirect('/services')
            ->with('success', 'Service updated successfully');
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);

        $user = Auth::user();

        // SECURITY
        if (
            !$user->hasRole('super_admin')
            && $service->provider_id != $user->provider_id
        ) {
            abort(403);
        }

        $service->delete();

        return redirect('/services')
            ->with('success', 'Service deleted successfully');
    }
}