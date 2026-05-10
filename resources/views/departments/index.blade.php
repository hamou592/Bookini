@extends('admin.layout')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold" style="color: var(--text-main);">
        Departments
    </h2>

    <a href="/departments/create"
       style="background-color: var(--primary);"
       onmouseover="this.style.backgroundColor='var(--primary-hover)'"
       onmouseout="this.style.backgroundColor='var(--primary)'"
       class="text-white px-4 py-2 rounded-lg shadow">
        + Add Department
    </a>
</div>

<!-- SEARCH -->
@if(auth()->user()->hasRole('super_admin'))

<!-- SEARCH -->
<input id="searchInput"
    placeholder="Search by provider..."
    class="w-full p-3 border rounded-xl mb-6 focus:ring-2"
    style="border-color:#e5e7eb;">

@endif
<!-- TABLE -->
<div class="bg-white rounded-2xl shadow border overflow-hidden">

    <div id="departmentsContent">
        @include('departments.partials.table')
    </div>

</div>
<script>

const searchInput = document.getElementById('searchInput');

let debounceTimer;

function fetchDepartments(page = 1) {

    let search = '';

    // ONLY if search exists
    if (searchInput) {
        search = searchInput.value;
    }

    fetch(`/departments?page=${page}&search=${search}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.text())
    .then(html => {

        document.getElementById(
            'departmentsContent'
        ).innerHTML = html;

    });
}

// SEARCH EVENT ONLY FOR SUPER ADMIN
if (searchInput) {

    searchInput.addEventListener('keyup', () => {

        clearTimeout(debounceTimer);

        debounceTimer = setTimeout(() => {

            fetchDepartments();

        }, 300);

    });
}

// AJAX PAGINATION
document.addEventListener('click', function(e) {

    if (e.target.closest('#paginationWrapper a')) {

        e.preventDefault();

        const url = e.target.closest('a').href;

        const page = new URL(url)
            .searchParams
            .get('page');

        fetchDepartments(page);
    }
});

</script>
@endsection