@extends('admin.layout')

@section('content')

<h2 class="text-3xl font-bold mb-6"
    style="color: var(--text-main);">
    Edit Service
</h2>

<div class="bg-white p-8 rounded-2xl shadow">

<form method="POST" action="/services/{{ $service->id }}">

    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- SUPER ADMIN ONLY --}}
        @if(auth()->user()->hasRole('super_admin'))

        <div>
            <label class="block mb-2 font-medium">
                Provider
            </label>

            <select name="provider_id"
                    id="providerSelect"
                    class="w-full border rounded-xl p-3">

                @foreach($providers as $provider)

                    <option value="{{ $provider->id }}"
                        {{ $service->provider_id == $provider->id ? 'selected' : '' }}>

                        {{ $provider->name }}

                    </option>

                @endforeach

            </select>
        </div>

        @else

            <input type="hidden"
                   name="provider_id"
                   value="{{ auth()->user()->provider_id }}">

        @endif

        {{-- DEPARTMENT --}}
        <div id="departmentWrapper"
    @if(
        !auth()->user()->hasRole('super_admin')
        &&
        auth()->user()->provider?->type != 'clinic'
    )
        style="display:none;"
    @endif>
            <label class="block mb-2 font-medium">
                Department
            </label>

            <select name="department_id"
                    id="departmentSelect"
                    class="w-full border rounded-xl p-3">

                <option value="">
                    No Department
                </option>

                @foreach($departments as $department)

                    <option value="{{ $department->id }}"
                        {{ $service->department_id == $department->id ? 'selected' : '' }}>

                        {{ $department->name }}

                    </option>

                @endforeach

            </select>
        </div>

        <div>
            <label class="block mb-2 font-medium">
                Service Name
            </label>

            <input type="text"
                   name="name"
                   value="{{ $service->name }}"
                   class="w-full border rounded-xl p-3">
        </div>

        <div>
            <label class="block mb-2 font-medium">
                Price
            </label>

            <input type="number"
                   name="price"
                   value="{{ $service->price }}"
                   class="w-full border rounded-xl p-3">
        </div>

        <div>
            <label class="block mb-2 font-medium">
                Duration (minutes)
            </label>

            <input type="number"
                   name="duration"
                   value="{{ $service->duration }}"
                   class="w-full border rounded-xl p-3">
        </div>

        <div>
            <label class="block mb-2 font-medium">
                Status
            </label>

            <select name="is_active"
                    class="w-full border rounded-xl p-3">

                <option value="1"
                    {{ $service->is_active ? 'selected' : '' }}>
                    Active
                </option>

                <option value="0"
                    {{ !$service->is_active ? 'selected' : '' }}>
                    Inactive
                </option>

            </select>
        </div>

    </div>

    <button class="mt-6 px-6 py-3 rounded-xl text-white font-medium"
            style="background: var(--primary);">

        Update Service

    </button>

</form>

</div>

{{-- SUPER ADMIN SCRIPT --}}
@if(auth()->user()->hasRole('super_admin'))

<script>

const providerSelect = document.getElementById('providerSelect');
const departmentSelect = document.getElementById('departmentSelect');

const allDepartments = @json(
    \App\Models\Department::all()
);

const allProviders = @json(
    \App\Models\Provider::all()
);

const departmentWrapper =
    document.getElementById(
        'departmentWrapper'
    );

providerSelect.addEventListener('change', function () {

    const providerId = this.value;

    const provider = allProviders.find(
        p => p.id == providerId
    );

    // RESET
    departmentSelect.innerHTML =
        '<option value="">Select Department</option>';

    // NO PROVIDER
    if (!providerId) {

        departmentSelect.disabled = true;

        departmentWrapper.style.display = 'none';

        return;
    }

    // DOCTOR => HIDE DEPARTMENT
    if (provider.type != 'clinic') {

        departmentWrapper.style.display = 'none';

        departmentSelect.disabled = true;

        return;
    }

    // CLINIC => SHOW
    departmentWrapper.style.display = 'block';

    departmentSelect.disabled = false;

    const filteredDepartments =
        allDepartments.filter(
            dept => dept.provider_id == providerId
        );

    filteredDepartments.forEach(department => {

        departmentSelect.innerHTML += `
            <option value="${department.id}">
                ${department.name}
            </option>
        `;
    });
});
providerSelect.dispatchEvent(
    new Event('change')
);

</script>

@endif

@endsection