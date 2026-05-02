@extends('admin.layout')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold text-gray-800">Users</h2>

    <a href="/users/create"
       class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
        + Add User
    </a>
</div>

<div class="bg-white rounded-2xl shadow border border-gray-200 overflow-hidden">

<table class="w-full text-left border-collapse">
    <thead class="bg-gray-50 text-gray-600 text-sm">
        <tr>
            <th class="p-4">Name</th>
            <th class="p-4">Email</th>
            <th class="p-4">Role</th>
            <th class="p-4 text-right">Actions</th>
        </tr>
    </thead>

    <tbody class="text-gray-700">
        @foreach($users as $user)
        <tr class="border-t hover:bg-gray-50 transition">

            <!-- Name -->
            <td class="p-4 font-medium align-middle">
                {{ $user->name }}
            </td>

            <!-- Email -->
            <td class="p-4 align-middle">
                {{ $user->email }}
            </td>

            <!-- Role -->
            <td class="p-4 align-middle">
                <span class="inline-block bg-indigo-100 text-indigo-600 px-3 py-1 rounded-full text-xs font-medium">
                    {{ $user->roles->first()?->name ?? 'No role' }}
                </span>
            </td>

            <!-- Actions -->
            <td class="p-4 align-middle text-right">
                <div class="inline-flex items-center gap-2">

                    <!-- Edit -->
                    <a href="/users/{{ $user->id }}/edit"
   style="background-color: var(--primary);"
   onmouseover="this.style.backgroundColor='var(--primary-hover)'"
   onmouseout="this.style.backgroundColor='var(--primary)'"
   class="text-white px-3 py-1 rounded-lg transition text-sm">
    Edit
</a>

                    <!-- Delete -->
                    <form method="POST" action="/users/{{ $user->id }}"
                          onsubmit="return confirm('Are you sure you want to delete this user?');">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition text-sm">
                            Delete
                        </button>
                    </form>

                </div>
            </td>

        </tr>
        @endforeach
    </tbody>
</table>

</div>

@endsection