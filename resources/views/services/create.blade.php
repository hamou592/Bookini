@extends('admin.layout')

@section('content')

<h2 class="text-3xl font-bold mb-6"
    style="color: var(--text-main);">

    Create Service

</h2>

<div class="bg-white p-8 rounded-2xl shadow">

<form method="POST"
      action="/services"
      id="serviceForm">

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

                {{-- NON SUPER ADMIN --}}
                @unless(auth()->user()->hasRole('super_admin'))

                    @foreach($departments as $department)

                        <option value="{{ $department->id }}">
                            {{ $department->name }}
                        </option>

                    @endforeach

                @endunless

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

<script>

const providerField =
    document.getElementById('providerSelect');

const departmentField =
    document.getElementById('departmentSelect');

const departmentWrapper =
    document.getElementById('departmentWrapper');

const nameField =
    document.getElementById('serviceName');

const priceField =
    document.getElementById('price');

const durationField =
    document.getElementById('duration');

const submitBtn =
    document.getElementById('submitBtn');

const allProviders = @json(
    \App\Models\Provider::all()
);

const allDepartments = @json(
    \App\Models\Department::all()
);

function isClinicProvider() {

    @if(auth()->user()->hasRole('super_admin'))

        const providerId = providerField.value;

        if (!providerId) {
            return false;
        }

        const provider = allProviders.find(
            p => p.id == providerId
        );

        return provider &&
               provider.type &&
               provider.type.toLowerCase() === 'clinic';

    @else

        return @json(
            auth()->user()->provider?->type == 'clinic'
        );

    @endif
}

function loadDepartments(providerId) {

    departmentField.innerHTML =
        '<option value="">Select Department</option>';

    const filteredDepartments =
        allDepartments.filter(
            dept => dept.provider_id == providerId
        );

    filteredDepartments.forEach(
        department => {

            departmentField.innerHTML += `
                <option value="${department.id}">
                    ${department.name}
                </option>
            `;
        }
    );
}

function validateForm(showErrors = false) {

    let valid = true;

    // =========================
    // PROVIDER VALIDATION
    // =========================

    @if(auth()->user()->hasRole('super_admin'))

    if (!providerField.value) {

        valid = false;

        if (showErrors) {

            providerField.classList.add(
                'border-red-500'
            );
        }

    } else {

        providerField.classList.remove(
            'border-red-500'
        );
    }

    @endif

    // =========================
    // DEPARTMENT VALIDATION
    // =========================

    const departmentRequired =
        isClinicProvider();

    if (
        departmentRequired &&
        departmentWrapper.style.display !== 'none'
    ) {

        if (!departmentField.value) {

            valid = false;

            if (showErrors) {

                document
                    .getElementById('departmentError')
                    .classList.remove('hidden');

                departmentField.classList.add(
                    'border-red-500'
                );
            }

        } else {

            document
                .getElementById('departmentError')
                .classList.add('hidden');

            departmentField.classList.remove(
                'border-red-500'
            );
        }

    } else {

        document
            .getElementById('departmentError')
            .classList.add('hidden');

        departmentField.classList.remove(
            'border-red-500'
        );
    }

    // =========================
    // NAME VALIDATION
    // =========================

    if (!nameField.value.trim()) {

        valid = false;

        if (showErrors) {

            document
                .getElementById('nameError')
                .classList.remove('hidden');

            nameField.classList.add(
                'border-red-500'
            );
        }

    } else {

        document
            .getElementById('nameError')
            .classList.add('hidden');

        nameField.classList.remove(
            'border-red-500'
        );
    }

    // =========================
    // PRICE VALIDATION
    // =========================

    if (!priceField.value) {

        valid = false;

        if (showErrors) {

            document
                .getElementById('priceError')
                .classList.remove('hidden');

            priceField.classList.add(
                'border-red-500'
            );
        }

    } else {

        document
            .getElementById('priceError')
            .classList.add('hidden');

        priceField.classList.remove(
            'border-red-500'
        );
    }

    // =========================
    // DURATION VALIDATION
    // =========================

    if (!durationField.value) {

        valid = false;

        if (showErrors) {

            document
                .getElementById('durationError')
                .classList.remove('hidden');

            durationField.classList.add(
                'border-red-500'
            );
        }

    } else {

        document
            .getElementById('durationError')
            .classList.add('hidden');

        durationField.classList.remove(
            'border-red-500'
        );
    }

    // =========================
    // BUTTON STATE
    // =========================

    submitBtn.disabled = !valid;

    if (valid) {

        submitBtn.classList.remove(
            'opacity-50',
            'cursor-not-allowed'
        );

    } else {

        submitBtn.classList.add(
            'opacity-50',
            'cursor-not-allowed'
        );
    }

    return valid;
}

@if(auth()->user()->hasRole('super_admin'))

providerField.addEventListener(
    'change',
    function () {

        const providerId = this.value;

        const provider = allProviders.find(
            p => p.id == providerId
        );

        // RESET
        departmentField.innerHTML =
            '<option value="">Select Department</option>';

        departmentField.value = '';

        // NO PROVIDER
        if (!providerId) {

            departmentWrapper.style.display =
                'none';

            departmentField.disabled = true;

            validateForm(false);

            return;
        }

        // DOCTOR PROVIDER
        if (
            !provider ||
            provider.type.toLowerCase() !== 'clinic'
        ) {

            departmentWrapper.style.display =
                'none';

            departmentField.disabled = true;

            departmentField.value = '';

            validateForm(false);

            return;
        }

        // CLINIC PROVIDER
        departmentWrapper.style.display =
            'block';

        departmentField.disabled = false;

        loadDepartments(providerId);

        validateForm(false);
    }
);

@endif

// =========================
// LIVE VALIDATION
// =========================

departmentField?.addEventListener(
    'change',
    () => validateForm(false)
);

nameField.addEventListener(
    'input',
    () => validateForm(false)
);

priceField.addEventListener(
    'input',
    () => validateForm(false)
);

durationField.addEventListener(
    'input',
    () => validateForm(false)
);

// =========================
// SUBMIT VALIDATION
// =========================

document.querySelector('form')
.addEventListener(
    'submit',
    function(e) {

        if (!validateForm(true)) {

            e.preventDefault();

            const firstInvalid =
                document.querySelector(
                    '.border-red-500'
                );

            if (firstInvalid) {
                firstInvalid.focus();
            }
        }
    }
);

// INITIAL VALIDATION
validateForm(false);

</script>

@endsection