@extends('admin.layout')

@section('content')

<h2 class="text-2xl font-semibold mb-6">Create User</h2>

<form id="createUserForm" method="POST" action="/users" class="space-y-5 bg-white p-6 rounded-2xl shadow border" enctype="multipart/form-data">
    @csrf

    <!-- USER INFO -->
    <input name="name" placeholder="Name" class="w-full border p-2 rounded-lg">
    <input id="emailInput" name="email" placeholder="Email"
    class="w-full border p-2 rounded-lg">
<p id="emailError" class="text-red-500 text-sm mt-1 hidden"></p>
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

        <input type="file" id="logoInput" name="new_provider_logo"
    class="w-full border p-2 rounded-lg">

<p id="logoError" class="text-red-500 text-sm mt-1 hidden"></p>

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

    <button id="submitBtn" style="background-color: var(--primary);"
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

<script>
const form = document.getElementById('createUserForm');

const emailInput = document.getElementById('emailInput');
const logoInput = document.getElementById('logoInput');

const emailError = document.getElementById('emailError');
const logoError = document.getElementById('logoError');

// 🔍 EMAIL
function validateEmail() {
    const email = emailInput.value;
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!regex.test(email)) {
        emailError.textContent = "Invalid email format";
        emailError.classList.remove('hidden');
        emailInput.classList.add('border-red-500');
        return false;
    }

    emailError.classList.add('hidden');
    emailInput.classList.remove('border-red-500');
    return true;
}

// 🖼️ LOGO
function validateLogo() {
    const file = logoInput.files[0];

    if (!file) {
        logoError.classList.add('hidden');
        logoInput.classList.remove('border-red-500');
        return true;
    }

    const allowedTypes = ['image/jpeg', 'image/png'];
    const maxSize = 2 * 1024 * 1024;

    if (!allowedTypes.includes(file.type)) {
        logoError.textContent = "Only JPG or PNG allowed";
        logoError.classList.remove('hidden');
        logoInput.classList.add('border-red-500');
        return false;
    }

    if (file.size > maxSize) {
        logoError.textContent = "Max size is 2MB";
        logoError.classList.remove('hidden');
        logoInput.classList.add('border-red-500');
        return false;
    }

    logoError.classList.add('hidden');
    logoInput.classList.remove('border-red-500');
    return true;
}

// ✅ GLOBAL VALIDATOR
function validateForm() {
    let valid = true;

    if (!validateEmail()) valid = false;
    if (!validateLogo()) valid = false;

    return valid;
}

// 🎯 REAL-TIME
emailInput.addEventListener('input', validateEmail);
logoInput.addEventListener('change', validateLogo);

// 🚀 BLOCK SUBMIT
form.addEventListener('submit', function(e) {
    if (!validateForm()) {
        e.preventDefault(); // ❌ STOP form submit
    }
});
const submitBtn = document.getElementById('submitBtn');

function updateButtonState() {
    if (validateForm()) {
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    } else {
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
    }
}

// attach to events
emailInput.addEventListener('input', updateButtonState);
logoInput.addEventListener('change', updateButtonState);
</script>
@endsection