<?php


namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    public function index(Request $request)
{
    $query = Department::with('provider');

    // 🔐 ROLE FILTER
    if (!Auth::user()->hasRole('super_admin')) {
        $query->where('provider_id', Auth::user()->provider_id);
    }

    // 🔍 SEARCH
    if ($request->search) {

        // SUPER ADMIN
        if (Auth::user()->hasRole('super_admin')) {

            $query->where(function ($q) use ($request) {

                // Department name
                $q->where(
                    'name',
                    'like',
                    '%' . $request->search . '%'
                )

                // Provider name
                ->orWhereHas('provider', function ($providerQuery) use ($request) {

                    $providerQuery->where(
                        'name',
                        'like',
                        '%' . $request->search . '%'
                    );
                });

            });

        }

        // NON SUPER ADMIN
        else {

            $query->where(
                'name',
                'like',
                '%' . $request->search . '%'
            );

        }
    }

    $departments = $query
        ->latest()
        ->paginate(10)
        ->withQueryString();

    // AJAX
    if ($request->ajax()) {
        return view(
            'departments.partials.table',
            compact('departments')
        )->render();
    }

    return view(
        'departments.index',
        compact('departments')
    );
}
private function authorizeDepartment($providerId)
{
    $user = Auth::user();

    // SUPER ADMIN
    if ($user->hasRole('super_admin')) {
        return true;
    }

    // PROVIDER ADMIN / SECRETARY
    if (
        $user->hasRole([
            'provider_admin',
            'secretary'
        ])
        &&
        $user->provider_id == $providerId
    ) {
        return true;
    }

    abort(403);
}
    public function create()
{
    if (Auth::user()->hasRole('super_admin')) {
        $providers = Provider::where('type', 'clinic')->get();
    } else {
        $providers = Provider::where('id', Auth::user()->provider_id)
            ->where('type', 'clinic')
            ->get();
    }

    return view('departments.create', compact('providers'));
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'provider_id' => 'required|exists:providers,id',
    ]);

    $this->authorizeDepartment($request->provider_id);

    Department::create([
        'name' => $request->name,
        'provider_id' => $request->provider_id,
    ]);

    return redirect('/departments');
}

 public function edit($id)
{
    $department = Department::findOrFail($id);

    $this->authorizeDepartment($department->provider_id);

    if (Auth::user()->hasRole('super_admin')) {
        $providers = Provider::where('type', 'clinic')->get();
    } else {
        $providers = Provider::where('id', Auth::user()->provider_id)->get();
    }

    return view('departments.edit', compact('department', 'providers'));
}
 public function update(Request $request, $id)
{
    $department = Department::findOrFail($id);

    $request->validate([
        'name' => 'required',
        'provider_id' => 'required|exists:providers,id',
    ]);

    $this->authorizeDepartment($department->provider_id);

    $department->update([
        'name' => $request->name,
        'provider_id' => $request->provider_id,
    ]);

    return redirect('/departments');
}
public function destroy($id)
{
    $department = Department::findOrFail($id);

    $this->authorizeDepartment($department->provider_id);

    $department->delete();

    return redirect('/departments');
}
}