@extends('admin.layout')

@section('content')

<h2 class="text-xl mb-4">Create User</h2>

<form method="POST" action="/users" class="space-y-4">
    @csrf

    <input name="name" placeholder="Name" class="w-full border p-2 rounded">

    <input name="email" placeholder="Email" class="w-full border p-2 rounded">

    <input type="password" name="password" placeholder="Password" class="w-full border p-2 rounded">

    <select name="role_id" class="w-full border p-2 rounded">
        @foreach($roles as $role)
            <option value="{{ $role->id }}">{{ $role->name }}</option>
        @endforeach
    </select>

    <button class="bg-indigo-600 text-white px-4 py-2 rounded">
        Create
    </button>

</form>

@endsection