@extends('admin.layout')

@section('content')

<h2 class="text-2xl font-semibold mb-6" style="color: var(--text-main);">
    Edit User
</h2>

<form method="POST" action="/users/{{ $user->id }}"
      enctype="multipart/form-data"
      class="space-y-5 bg-white p-6 rounded-2xl shadow border">
    @csrf
    @method('PUT')

    <!-- Name -->
    <div>
        <label class="text-sm" style="color: var(--text-secondary);">Name</label>
        <input value="{{ $user->name }}" name="name"
            class="w-full p-2 rounded-lg border focus:ring-2">
    </div>

    <!-- Email -->
    <div>
        <label class="text-sm" style="color: var(--text-secondary);">Email</label>
        <input value="{{ $user->email }}" name="email"
            class="w-full p-2 rounded-lg border focus:ring-2">
    </div>

    <!-- Password -->
    <div>
        <label class="text-sm" style="color: var(--text-secondary);">
            New Password (optional)
        </label>
        <input type="password" name="password"
            placeholder="Leave empty to keep current password"
            class="w-full p-2 rounded-lg border">
    </div>

    <!-- Role -->
    <div>
        <label class="text-sm" style="color: var(--text-secondary);">Role</label>
        <select name="role_id" class="w-full p-2 rounded-lg border">
            @foreach($roles as $role)
                <option value="{{ $role->id }}"
                    {{ $user->roles->first()?->id == $role->id ? 'selected' : '' }}>
                    {{ $role->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- PROVIDER MODE -->
    <div>
        <label class="font-semibold">Provider</label>

        <div class="flex gap-4 mt-2">
            <label>
                <input type="radio" name="provider_mode" value="existing"
                       {{ $user->provider_id ? 'checked' : '' }}>
                Existing
            </label>

            <label>
                <input type="radio" name="provider_mode" value="new"
                       {{ !$user->provider_id ? 'checked' : '' }}>
                New
            </label>
        </div>
    </div>

    <!-- EXISTING PROVIDER -->
    <div id="existingProvider">
        <select name="provider_id" class="w-full border p-2 rounded-lg">
            <option value="">No provider</option>
            @foreach($providers as $provider)
                <option value="{{ $provider->id }}"
                    {{ $user->provider_id == $provider->id ? 'selected' : '' }}>
                    {{ $provider->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- NEW PROVIDER -->
    <div id="newProvider" class="hidden space-y-3">

        <input name="new_provider_name" placeholder="Provider Name"
            class="w-full border p-2 rounded-lg">

        <select name="new_provider_type" class="w-full border p-2 rounded-lg">
            <option value="clinic">Clinic</option>
            <option value="doctor">Doctor</option>
        </select>

        <input name="new_provider_phone" placeholder="Phone"
            class="w-full border p-2 rounded-lg">

        <!-- LOGO UPLOAD -->
        <input type="file" name="new_provider_logo"
            class="w-full border p-2 rounded-lg">

        <select name="new_provider_subscription_status"
            class="w-full border p-2 rounded-lg">
            <option value="inactive">Inactive</option>
            <option value="active">Active</option>
        </select>

        <!-- START DATE -->
        <input type="datetime-local"
            name="new_provider_subscription_start_at"
            value="{{ now()->format('Y-m-d\TH:i') }}"
            class="w-full border p-2 rounded-lg">

    </div>

    <!-- BUTTON -->
    <button
        style="background-color: var(--primary);"
        onmouseover="this.style.backgroundColor='var(--primary-hover)'"
        onmouseout="this.style.backgroundColor='var(--primary)'"
        class="text-white px-4 py-2 rounded-lg w-full">
        Update User
    </button>

</form>

<!-- TOGGLE SCRIPT -->
<script>
function toggleProviderMode() {
    const mode = document.querySelector('input[name="provider_mode"]:checked').value;

    document.getElementById('existingProvider').classList.toggle('hidden', mode !== 'existing');
    document.getElementById('newProvider').classList.toggle('hidden', mode !== 'new');
}

// INIT
document.querySelectorAll('input[name="provider_mode"]').forEach(el => {
    el.addEventListener('change', toggleProviderMode);
});

// RUN ON LOAD
toggleProviderMode();
</script>

@endsection