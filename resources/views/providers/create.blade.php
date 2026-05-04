@extends('admin.layout')

@section('content')

<h2 class="text-2xl font-semibold mb-6">Create Provider</h2>

<form method="POST" action="/providers" enctype="multipart/form-data" class="space-y-5">
    @csrf

    <input name="name" placeholder="Provider Name"
        class="w-full p-3 border rounded-lg">

    <select name="type" class="w-full p-3 border rounded-lg">
        <option value="clinic">Clinic</option>
        <option value="doctor">Doctor</option>
    </select>

    <input name="phone" placeholder="Phone"
        class="w-full p-3 border rounded-lg">

    <!-- LOGO -->
    <input type="file" name="logo"
        class="w-full p-3 border rounded-lg">

    <!-- SUBSCRIPTION -->
    <select name="subscription_status" class="w-full p-3 border rounded-lg">
        <option value="inactive">Inactive</option>
        <option value="active">Active</option>
    </select>

    <input 
        type="datetime-local" 
        name="subscription_start_at"
        value="{{ now()->format('Y-m-d\TH:i') }}"
        class="w-full p-3 border rounded-lg"
    >

    <button style="background-color: var(--primary);"
        class="text-white px-6 py-2 rounded-lg">
        Create
    </button>

</form>

@endsection