@extends('admin.layout')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold" style="color: var(--text-main);">
        Users
    </h2>

    <a href="/users/create"
       style="background-color: var(--primary);"
       class="text-white px-4 py-2 rounded-lg transition">
        + Add User
    </a>
</div>

<!-- 🔍 Search -->
<form method="GET" action="/users" class="mb-6">
    <input type="text" name="search" value="{{ request('search') }}"
        placeholder="Search name, email, provider..."
        class="w-full p-3 rounded-lg border"
        style="border-color:#e5e7eb;">
</form>

<div class="bg-white rounded-2xl shadow border border-gray-200 overflow-hidden">

<table class="w-full text-left border-collapse">
    <thead class="bg-gray-50 text-gray-600 text-sm">
        <tr>
            <th class="p-4">Name</th>
            <th class="p-4">Email</th>
            <th class="p-4">Provider</th>
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

            <!-- Provider -->
            <td class="p-4 align-middle">
                @if($user->provider)
                    <span class="px-2 py-1 rounded text-xs bg-gray-100">
                        {{ $user->provider->name }}
                    </span>
                @else
                    <span class="text-gray-400 text-sm">
                        —
                    </span>
                @endif
            </td>

            <!-- Role -->
            <td class="p-4 align-middle">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium"
                      style="background-color: rgba(79,70,229,0.1); color: var(--primary);">
                    {{ $user->roles->first()?->name ?? 'No role' }}
                </span>
            </td>

            <!-- Actions -->
            <td class="p-4 align-middle text-right">
                <div class="inline-flex items-center gap-2">

                    <!-- Edit -->
                    <a href="/users/{{ $user->id }}/edit"
                       style="background-color: var(--primary);"
                       class="text-white px-3 py-1 rounded-lg text-sm transition">
                        Edit
                    </a>

                    <!-- Delete -->
                    <form method="POST" action="/users/{{ $user->id }}"
                          onsubmit="return confirm('Delete this user?');">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="bg-red-500 text-white px-3 py-1 rounded-lg text-sm">
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

<!-- 📄 Pagination -->
<div class="mt-6">
    {{ $users->links() }}
</div>

@endsection