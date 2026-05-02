@extends('admin.layout')

@section('content')

<h2 class="text-2xl font-semibold mb-6">Create User</h2>

<form method="POST" action="/users" class="space-y-5 bg-white p-6 rounded-2xl shadow border">
    @csrf

    <div>
        <label class="text-sm text-gray-600">Name</label>
        <input name="name"
            class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-indigo-500">
    </div>

    <div>
        <label class="text-sm text-gray-600">Email</label>
        <input name="email"
            class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-indigo-500">
    </div>

    <div>
        <label class="text-sm text-gray-600">Password</label>
        <input type="password" name="password"
            class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-indigo-500">
    </div>

    <div>
        <label class="text-sm text-gray-600">Role</label>
        <select name="role_id" class="w-full border p-2 rounded-lg">
            @foreach($roles as $role)
                <option value="{{ $role->id }}">{{ $role->name }}</option>
            @endforeach
        </select>
    </div>

    <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
        Create User
    </button>

</form>

@endsection