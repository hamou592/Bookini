@extends('admin.layout')

@section('content')

<h2 class="text-2xl font-semibold mb-6">Edit Provider</h2>

<form method="POST" action="/providers/{{ $provider->id }}" enctype="multipart/form-data" class="space-y-5">
    @csrf
    @method('PUT')

    <input name="name" value="{{ $provider->name }}"
        class="w-full p-3 border rounded-lg">

    <select name="type" class="w-full p-3 border rounded-lg">
        <option value="clinic" {{ $provider->type == 'clinic' ? 'selected' : '' }}>Clinic</option>
        <option value="doctor" {{ $provider->type == 'doctor' ? 'selected' : '' }}>Doctor</option>
    </select>

    <input name="phone" value="{{ $provider->phone }}"
        class="w-full p-3 border rounded-lg">

    <!-- LOGO -->
    <input type="file" name="logo"
        class="w-full p-3 border rounded-lg">

    <!-- SUBSCRIPTION -->
    <select name="subscription_status" class="w-full p-3 border rounded-lg">
        <option value="inactive" {{ $provider->subscription_status == 'inactive' ? 'selected' : '' }}>Inactive</option>
        <option value="active" {{ $provider->subscription_status == 'active' ? 'selected' : '' }}>Active</option>
    </select>

    <input 
        type="datetime-local" 
        name="subscription_start_at"
        value="{{ $provider->subscription_start_at ? \Carbon\Carbon::parse($provider->subscription_start_at)->format('Y-m-d\TH:i') : '' }}"
        class="w-full p-3 border rounded-lg"
    >

    <button style="background-color: var(--primary);"
        class="text-white px-6 py-2 rounded-lg">
        Update
    </button>

</form>

@endsection