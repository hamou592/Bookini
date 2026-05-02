@extends('admin.layout')

@section('content')

<h2 class="text-2xl font-semibold mb-6" style="color: var(--text-main);">
    Edit User
</h2>

<form method="POST" action="/users/{{ $user->id }}"
      class="space-y-5 bg-white p-6 rounded-2xl shadow border">
    @csrf
    @method('PUT')

    <!-- Name -->
    <div>
        <label class="text-sm" style="color: var(--text-secondary);">Name</label>
        <input value="{{ $user->name }}" name="name"
            class="w-full p-2 rounded-lg border focus:ring-2"
            style="border-color:#e5e7eb;">
    </div>

    <!-- Email -->
    <div>
        <label class="text-sm" style="color: var(--text-secondary);">Email</label>
        <input value="{{ $user->email }}" name="email"
            class="w-full p-2 rounded-lg border focus:ring-2"
            style="border-color:#e5e7eb;">
    </div>

    <!-- Password -->
    <div>
        <label class="text-sm" style="color: var(--text-secondary);">
            New Password (optional)
        </label>
        <input type="password" name="password"
            placeholder="Leave empty to keep current password"
            class="w-full p-2 rounded-lg border focus:ring-2"
            style="border-color:#e5e7eb;">
    </div>

    <!-- Role -->
    <div>
        <label class="text-sm" style="color: var(--text-secondary);">Role</label>
        <select name="role_id"
            class="w-full p-2 rounded-lg border"
            style="border-color:#e5e7eb;">
            @foreach($roles as $role)
                <option value="{{ $role->id }}"
                    {{ $user->roles->first()?->id == $role->id ? 'selected' : '' }}>
                    {{ $role->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Button -->
    <button
        style="background-color: var(--primary);"
        onmouseover="this.style.backgroundColor='var(--primary-hover)'"
        onmouseout="this.style.backgroundColor='var(--primary)'"
        class="text-white px-4 py-2 rounded-lg transition w-full">
        Update User
    </button>

</form>

@endsection