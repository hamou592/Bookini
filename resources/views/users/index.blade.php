@extends('admin.layout')

@section('content')

<div class="flex justify-between mb-4">
    <h2 class="text-xl font-semibold">Users</h2>

    <a href="/users/create"
       class="bg-indigo-600 text-white px-4 py-2 rounded-lg">
        Add User
    </a>
</div>

<table class="w-full border border-gray-200 rounded-lg">
    <thead class="bg-gray-100">
        <tr>
            <th class="p-2">Name</th>
            <th class="p-2">Email</th>
            <th class="p-2">Actions</th>
        </tr>
    </thead>

    <tbody>
        @foreach($users as $user)
        <tr class="border-t">
            <td class="p-2">{{ $user->name }}</td>
            <td class="p-2">{{ $user->email }}</td>

            <td class="p-2 flex gap-2">
                <a href="/users/{{ $user->id }}/edit"
                   class="bg-yellow-400 px-3 py-1 rounded">
                    Edit
                </a>

                <form method="POST" action="/users/{{ $user->id }}">
                    @csrf
                    @method('DELETE')
                    <button class="bg-red-500 text-white px-3 py-1 rounded">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection