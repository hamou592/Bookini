@extends('admin.layout')

@section('content')

<h2 class="text-2xl font-semibold mb-6">Create User</h2>

<form method="POST" action="/users" class="space-y-5 bg-white p-6 rounded-2xl shadow border" enctype="multipart/form-data">
    @csrf

    <!-- USER INFO -->
    <input name="name" placeholder="Name" class="w-full border p-2 rounded-lg">
    <input name="email" placeholder="Email" class="w-full border p-2 rounded-lg">
    <input type="password" name="password" placeholder="Password" class="w-full border p-2 rounded-lg">

    <select name="role_id" class="w-full border p-2 rounded-lg">
        @foreach($roles as $role)
            <option value="{{ $role->id }}">{{ $role->name }}</option>
        @endforeach
    </select>

    <!-- PROVIDER MODE -->
    <div>
        <label class="font-semibold">Provider</label>

        <div class="flex gap-4 mt-2">
            <label><input type="radio" name="provider_mode" value="existing" checked> Existing</label>
            <label><input type="radio" name="provider_mode" value="new"> New</label>
        </div>
    </div>

    <!-- EXISTING -->
    <div id="existingProvider">
        <select name="provider_id" class="w-full border p-2 rounded-lg">
            <option value="">No provider</option>
            @foreach($providers as $provider)
                <option value="{{ $provider->id }}">{{ $provider->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- NEW PROVIDER FULL -->
    <div id="newProvider" class="hidden space-y-3">

        <input name="new_provider_name" placeholder="Provider Name" class="w-full border p-2 rounded-lg">

        <select name="new_provider_type" class="w-full border p-2 rounded-lg">
            <option value="clinic">Clinic</option>
            <option value="doctor">Doctor</option>
        </select>

        <input name="new_provider_phone" placeholder="Phone" class="w-full border p-2 rounded-lg">

        <input type="file" name="new_provider_logo"
    class="w-full border p-2 rounded-lg">

        <select name="new_provider_subscription_status" class="w-full border p-2 rounded-lg">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
        
        <input 
    type="datetime-local" 
    name="new_provider_subscription_start_at"
    value="{{ now()->format('Y-m-d\TH:i') }}"
    class="w-full border p-2 rounded-lg"
>
    </div>

    <button style="background-color: var(--primary);"
        class="text-white px-4 py-2 rounded-lg w-full">
        Create User
    </button>

</form>

<script>
document.querySelectorAll('input[name="provider_mode"]').forEach(el => {
    el.addEventListener('change', function () {
        document.getElementById('existingProvider').classList.toggle('hidden', this.value !== 'existing');
        document.getElementById('newProvider').classList.toggle('hidden', this.value !== 'new');
    });
});
</script>

@endsection