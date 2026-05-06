@extends('admin.layout')

@section('content')

<h2 class="text-2xl font-semibold mb-6">Edit Provider</h2>

<form id="editProviderForm" method="POST" action="/providers/{{ $provider->id }}"
      enctype="multipart/form-data"
      class="space-y-5">
    @csrf
    @method('PUT')

    <!-- NAME -->
    <div>
        <input id="nameInput"
            name="name"
            value="{{ $provider->name }}"
            class="w-full p-3 border rounded-lg">

        <p id="nameError" class="text-red-500 text-sm mt-1 hidden"></p>
    </div>

    <!-- TYPE -->
    <select name="type" class="w-full p-3 border rounded-lg">
        <option value="clinic" {{ $provider->type == 'clinic' ? 'selected' : '' }}>Clinic</option>
        <option value="doctor" {{ $provider->type == 'doctor' ? 'selected' : '' }}>Doctor</option>
    </select>

    <!-- PHONE -->
    <div>
        <input id="phoneInput"
            name="phone"
            value="{{ $provider->phone }}"
            class="w-full p-3 border rounded-lg">

        <p id="phoneError" class="text-red-500 text-sm mt-1 hidden"></p>
    </div>

    <!-- LOGO -->
    <div>
        <input type="file" id="logoInput" name="logo"
            class="w-full p-3 border rounded-lg">

        <p id="logoError" class="text-red-500 text-sm mt-1 hidden"></p>
    </div>

    <!-- STATUS -->
    <select name="subscription_status" class="w-full p-3 border rounded-lg">
        <option value="inactive" {{ $provider->subscription_status == 'inactive' ? 'selected' : '' }}>Inactive</option>
        <option value="active" {{ $provider->subscription_status == 'active' ? 'selected' : '' }}>Active</option>
    </select>

    <!-- START DATE -->
    <input 
        type="datetime-local" 
        name="subscription_start_at"
        value="{{ $provider->subscription_start_at ? \Carbon\Carbon::parse($provider->subscription_start_at)->format('Y-m-d\TH:i') : '' }}"
        class="w-full p-3 border rounded-lg"
    >

    <!-- BUTTON -->
    <button id="submitBtn"
        style="background-color: var(--primary);"
        class="text-white px-6 py-2 rounded-lg w-full">
        Update
    </button>

</form>
<script>
const form = document.getElementById('editProviderForm');

const nameInput = document.getElementById('nameInput');
const phoneInput = document.getElementById('phoneInput');
const logoInput = document.getElementById('logoInput');

const nameError = document.getElementById('nameError');
const phoneError = document.getElementById('phoneError');
const logoError = document.getElementById('logoError');

const submitBtn = document.getElementById('submitBtn');

// 🧠 NAME
function validateName() {
    if (nameInput.value.trim() === '') {
        nameError.textContent = "Name is required";
        nameError.classList.remove('hidden');
        nameInput.classList.add('border-red-500');
        return false;
    }

    nameError.classList.add('hidden');
    nameInput.classList.remove('border-red-500');
    return true;
}

// 📞 PHONE (optional)
function validatePhone() {
    const phone = phoneInput.value.trim();

    if (phone === '') {
        phoneError.classList.add('hidden');
        phoneInput.classList.remove('border-red-500');
        return true;
    }

    const regex = /^[0-9+\s\-]{6,20}$/;

    if (!regex.test(phone)) {
        phoneError.textContent = "Invalid phone format";
        phoneError.classList.remove('hidden');
        phoneInput.classList.add('border-red-500');
        return false;
    }

    phoneError.classList.add('hidden');
    phoneInput.classList.remove('border-red-500');
    return true;
}

// 🖼️ LOGO (optional in edit)
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

// ✅ GLOBAL
function validateForm() {
    let valid = true;

    if (!validateName()) valid = false;
    if (!validatePhone()) valid = false;
    if (!validateLogo()) valid = false;

    return valid;
}

// 🔘 BUTTON STATE
function updateButtonState() {
    if (validateForm()) {
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    } else {
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
    }
}

// 🎯 EVENTS
nameInput.addEventListener('input', updateButtonState);
phoneInput.addEventListener('input', updateButtonState);
logoInput.addEventListener('change', updateButtonState);

// 🚫 BLOCK SUBMIT
form.addEventListener('submit', function(e) {
    if (!validateForm()) {
        e.preventDefault();
    }
});

// INIT
updateButtonState();
</script>

@endsection