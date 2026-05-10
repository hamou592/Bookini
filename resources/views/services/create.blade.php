@extends('admin.layout')

@section('content')

<h2 class="text-3xl font-bold mb-6"
    style="color: var(--text-main);">

    Create Service

</h2>

<div class="bg-white p-8 rounded-2xl shadow">

<form method="POST" action="/services">

    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- SUPER ADMIN ONLY --}}
        @if(auth()->user()->hasRole('super_admin'))

        <div>

            <label class="block mb-2 font-medium">
                Provider
            </label>

            <select name="provider_id"
                    id="providerSelect"
                    class="w-full border rounded-xl p-3 focus:ring-2 focus:outline-none"
                    style="border-color:#E5E7EB;">

                <option value="">
                    Select Provider
                </option>

                @foreach($providers as $provider)

                    <option value="{{ $provider->id }}">
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
                    class="w-full border rounded-xl p-3 focus:ring-2 focus:outline-none"
                    style="border-color:#E5E7EB;"
                    @if(auth()->user()->hasRole('super_admin')) disabled @endif>

                <option value="">
                    Select Department
                </option>

                @foreach($departments as $department)

                    <option value="{{ $department->id }}"
                            data-provider="{{ $department->provider_id }}">

                        {{ $department->name }}

                    </option>

                @endforeach

            </select>

            <p id="departmentError"
               class="text-red-500 text-sm mt-2 hidden">

                Department is required

            </p>

        </div>

        {{-- SERVICE NAME --}}
        <div>

            <label class="block mb-2 font-medium">
                Service Name
            </label>

            <input type="text"
                   name="name"
                   id="serviceName"
                   placeholder="Enter service name"
                   class="w-full border rounded-xl p-3 focus:ring-2 focus:outline-none"
                   style="border-color:#E5E7EB;">

            <p id="nameError"
               class="text-red-500 text-sm mt-2 hidden">

                Service name is required

            </p>

        </div>

        {{-- PRICE --}}
        <div>

            <label class="block mb-2 font-medium">
                Price
            </label>

            <input type="number"
                   name="price"
                   id="price"
                   placeholder="Enter price"
                   class="w-full border rounded-xl p-3 focus:ring-2 focus:outline-none"
                   style="border-color:#E5E7EB;">

            <p id="priceError"
               class="text-red-500 text-sm mt-2 hidden">

                Price is required

            </p>

        </div>

        {{-- DURATION --}}
        <div>

            <label class="block mb-2 font-medium">
                Duration (minutes)
            </label>

            <input type="number"
                   name="duration"
                   id="duration"
                   placeholder="Enter duration"
                   class="w-full border rounded-xl p-3 focus:ring-2 focus:outline-none"
                   style="border-color:#E5E7EB;">

            <p id="durationError"
               class="text-red-500 text-sm mt-2 hidden">

                Duration is required

            </p>

        </div>

        {{-- STATUS --}}
        <div>

            <label class="block mb-2 font-medium">
                Status
            </label>

            <select name="is_active"
                    class="w-full border rounded-xl p-3"
                    style="border-color:#E5E7EB;">

                <option value="1">
                    Active
                </option>

                <option value="0">
                    Inactive
                </option>

            </select>

        </div>

    </div>

    {{-- BUTTON --}}
    <button
        id="submitBtn"
        disabled
        class="mt-6 px-6 py-3 rounded-xl text-white font-medium transition opacity-50 cursor-not-allowed"
        style="background: var(--primary);">

        Create Service

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
</script>

@endif

<script>

const departmentField = document.getElementById('departmentSelect');

const nameField = document.getElementById('serviceName');

const priceField = document.getElementById('price');

const durationField = document.getElementById('duration');

const submitBtn = document.getElementById('submitBtn');

function validateForm() {

    let valid = true;

    // DEPARTMENT
    if (!departmentField.value) {

        document
            .getElementById('departmentError')
            .classList.remove('hidden');

        if (valid) {
            departmentField.focus();
        }

        valid = false;

    } else {

        document
            .getElementById('departmentError')
            .classList.add('hidden');
    }

    // NAME
    if (!nameField.value.trim()) {

        document
            .getElementById('nameError')
            .classList.remove('hidden');

        if (valid) {
            nameField.focus();
        }

        valid = false;

    } else {

        document
            .getElementById('nameError')
            .classList.add('hidden');
    }

    // PRICE
    if (!priceField.value) {

        document
            .getElementById('priceError')
            .classList.remove('hidden');

        if (valid) {
            priceField.focus();
        }

        valid = false;

    } else {

        document
            .getElementById('priceError')
            .classList.add('hidden');
    }

    // DURATION
    if (!durationField.value) {

        document
            .getElementById('durationError')
            .classList.remove('hidden');

        if (valid) {
            durationField.focus();
        }

        valid = false;

    } else {

        document
            .getElementById('durationError')
            .classList.add('hidden');
    }

    // BUTTON STATE
    if (valid) {

        submitBtn.disabled = false;

        submitBtn.classList.remove(
            'opacity-50',
            'cursor-not-allowed'
        );

    } else {

        submitBtn.disabled = true;

        submitBtn.classList.add(
            'opacity-50',
            'cursor-not-allowed'
        );
    }
}

// EVENTS
departmentField.addEventListener('change', validateForm);

nameField.addEventListener('input', validateForm);

priceField.addEventListener('input', validateForm);

durationField.addEventListener('input', validateForm);

// INITIAL
validateForm();

</script>

@endsection