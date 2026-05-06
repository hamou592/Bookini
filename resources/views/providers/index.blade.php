@extends('admin.layout')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold" style="color: var(--text-main);">
        Providers
    </h2>

    <a href="/providers/create"
       style="background-color: var(--primary);"
       onmouseover="this.style.backgroundColor='var(--primary-hover)'"
       onmouseout="this.style.backgroundColor='var(--primary)'"
       class="text-white px-4 py-2 rounded-lg transition">
        + Add Provider
    </a>
</div>

<form id="filterForm" class="mb-6 flex flex-wrap gap-3">

    <input id="searchInput"
        type="text"
        placeholder="Search name or phone..."
        class="p-3 border rounded-xl flex-1">

    <select id="typeFilter" class="p-3 border rounded-xl">
        <option value="">All Types</option>
        <option value="clinic">Clinic</option>
        <option value="doctor">Doctor</option>
    </select>

    <select id="statusFilter" class="p-3 border rounded-xl">
        <option value="">All Status</option>
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
    </select>

</form>

<div class="bg-white rounded-2xl shadow border overflow-hidden">

<table class="w-full text-sm">

    <thead class="bg-gray-50 text-gray-600">
        <tr>
            <th class="p-4 text-left">Logo</th>
            <th class="p-4 text-left">Name</th>
            <th class="p-4 text-left">Type</th>
            <th class="p-4 text-left">Phone</th>
            <th class="p-4 text-left">Status</th>
            <th class="p-4 text-left">Start</th>
            <th class="p-4 text-left">Expires</th>
            <th class="p-4 text-right">Actions</th>
        </tr>
    </thead>

    @include('providers.partials.table')

</table>

</div>
<!-- ✅ Pagination OUTSIDE table -->
<div id="paginationWrapper" class="mt-6">
    {{ $providers->links() }}
</div>
<!-- IMAGE MODAL -->
<div id="imageModal" 
     class="fixed inset-0 bg-black bg-opacity-70 hidden items-center justify-center z-50">

    <img id="modalImage" class="max-w-lg rounded-lg shadow-lg">

</div>

<script>
function openImage(src) {
    document.getElementById('modalImage').src = src;
    document.getElementById('imageModal').classList.remove('hidden');
    document.getElementById('imageModal').classList.add('flex');
}

document.getElementById('imageModal').onclick = function () {
    this.classList.add('hidden');
};
</script>


<script>
const searchInput = document.getElementById('searchInput');
const typeFilter = document.getElementById('typeFilter');
const statusFilter = document.getElementById('statusFilter');

let debounceTimer;

function fetchProviders(page = 1) {
    const search = searchInput.value;
    const type = typeFilter.value;
    const status = statusFilter.value;

    fetch(`/providers?page=${page}&search=${search}&type=${type}&status=${status}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.text())
    .then(html => {
        document.getElementById('providersTable').outerHTML = html;

        // update pagination
        fetch(`/providers?page=${page}&search=${search}&type=${type}&status=${status}`)
            .then(res => res.text())
            .then(full => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(full, 'text/html');
                document.getElementById('paginationWrapper').innerHTML =
                    doc.getElementById('paginationWrapper').innerHTML;
            });
    });
}

// debounce
function debounceFetch() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => fetchProviders(), 300);
}

// events
searchInput.addEventListener('keyup', debounceFetch);
typeFilter.addEventListener('change', () => fetchProviders());
statusFilter.addEventListener('change', () => fetchProviders());

// pagination click
document.addEventListener('click', function(e) {
    if (e.target.closest('#paginationWrapper a')) {
        e.preventDefault();
        const url = e.target.closest('a').href;
        const page = new URL(url).searchParams.get('page');
        fetchProviders(page);
    }
});
</script>

@endsection
