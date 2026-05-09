@extends('admin.layout')

@section('content')

<h2 class="text-3xl font-bold mb-6"
    style="color: var(--text-main);">
    Create Service
</h2>

<div class="bg-white p-8 rounded-2xl shadow">

<form method="POST" action="/services">

    @csrf

    <div class="grid grid-cols-2 gap-6">

        <div>
            <label class="block mb-2 font-medium">
                Provider
            </label>

            <select name="provider_id"
                    class="w-full border rounded-xl p-3">

                @foreach($providers as $provider)

                    <option value="{{ $provider->id }}">
                        {{ $provider->name }}
                    </option>

                @endforeach

            </select>
        </div>

        <div>
            <label class="block mb-2 font-medium">
                Department
            </label>

            <select name="department_id"
                    class="w-full border rounded-xl p-3">

                <option value="">
                    No Department
                </option>

                @foreach($departments as $department)

                    <option value="{{ $department->id }}">
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
                   class="w-full border rounded-xl p-3">
        </div>

        <div>
            <label class="block mb-2 font-medium">
                Price
            </label>

            <input type="number"
                   name="price"
                   class="w-full border rounded-xl p-3">
        </div>

        <div>
            <label class="block mb-2 font-medium">
                Duration (minutes)
            </label>

            <input type="number"
                   name="duration"
                   class="w-full border rounded-xl p-3">
        </div>

        <div>
            <label class="block mb-2 font-medium">
                Status
            </label>

            <select name="is_active"
                    class="w-full border rounded-xl p-3">

                <option value="1">
                    Active
                </option>

                <option value="0">
                    Inactive
                </option>

            </select>
        </div>

    </div>

    <button class="mt-6 px-6 py-3 rounded-xl text-white font-medium"
            style="background: var(--primary);">

        Create Service

    </button>

</form>

</div>

@endsection