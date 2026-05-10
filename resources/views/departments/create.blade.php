@extends('admin.layout')

@section('content')

<div class="max-w-2xl">

    <h2 class="text-3xl font-bold mb-6"
        style="color: var(--text-main);">

        Create Department

    </h2>

    <div class="bg-white rounded-2xl shadow p-8">

        <form method="POST" action="/departments">

            @csrf

            {{-- NAME --}}
            <div class="mb-6">

                <label class="block mb-2 font-medium">
                    Department Name
                </label>

                <input
                    id="departmentName"
                    name="name"
                    placeholder="Enter department name"
                    class="w-full border rounded-xl p-3 focus:outline-none focus:ring-2"
                    style="border-color:#E5E7EB;">

                <p id="nameError"
                   class="text-red-500 text-sm mt-2 hidden">

                    Department name is required

                </p>

            </div>

            {{-- PROVIDER --}}
            @if(auth()->user()->hasRole('super_admin'))

            <div class="mb-6">

                <label class="block mb-2 font-medium">
                    Provider
                </label>

                <select
                    name="provider_id"
                    class="w-full border rounded-xl p-3"
                    style="border-color:#E5E7EB;">

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

            {{-- BUTTON --}}
            <button
                id="submitBtn"
                disabled
                class="px-6 py-3 rounded-xl text-white font-medium transition opacity-50 cursor-not-allowed"
                style="background-color: var(--primary);">

                Create Department

            </button>

        </form>

    </div>

</div>

<script>

const departmentName = document.getElementById('departmentName');

const submitBtn = document.getElementById('submitBtn');

const nameError = document.getElementById('nameError');

function validateForm() {

    const value = departmentName.value.trim();

    if (value.length > 0) {

        submitBtn.disabled = false;

        submitBtn.classList.remove(
            'opacity-50',
            'cursor-not-allowed'
        );

        nameError.classList.add('hidden');

    } else {

        submitBtn.disabled = true;

        submitBtn.classList.add(
            'opacity-50',
            'cursor-not-allowed'
        );

        nameError.classList.remove('hidden');
    }
}

departmentName.addEventListener(
    'input',
    validateForm
);

</script>

@endsection