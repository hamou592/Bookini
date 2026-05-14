@extends('admin.layout')

@section('content')

<div class="flex items-center justify-between mb-6">

    <h2 class="text-3xl font-bold"
        style="color: var(--text-main);">

        Services

    </h2>

    <a href="/services/create"
       class="px-5 py-3 rounded-xl text-white font-medium shadow"
       style="background: var(--primary);">

        + Add Service

    </a>

</div>

@if(session('success'))

    <div class="mb-4 p-4 rounded-xl bg-green-100 text-green-700">

        {{ session('success') }}

    </div>

@endif

@php

    $isSuperAdmin =
        auth()->user()->hasRole('super_admin');

    $isClinicProvider =
        auth()->user()->provider?->type === 'clinic';

@endphp
@php

    $searchPlaceholder =
        $isSuperAdmin
            ? 'Search service, provider or department...'
            : (
                $isClinicProvider
                    ? 'Search service or department...'
                    : 'Search service...'
            );

@endphp

<form class="mb-6 flex flex-wrap gap-3">

    <!-- SEARCH -->
    <input
        id="searchInput"
        type="text"
        placeholder="{{ $searchPlaceholder }}"
        class="p-3 border rounded-xl flex-1">

    <!-- STATUS -->
    <select
        id="statusFilter"
        class="p-3 border rounded-xl">

        <option value="">
            All Status
        </option>

        <option value="1">
            Active
        </option>

        <option value="0">
            Inactive
        </option>

    </select>

</form>
<div class="bg-white rounded-2xl shadow overflow-hidden">


    <div id="servicesContent">

        @include('services.partials.table')

    </div>

</div>
<script>

const searchInput =
    document.getElementById('searchInput');

const statusFilter =
    document.getElementById('statusFilter');

let debounceTimer;

function fetchServices(page = 1)
{
    const search =
        searchInput.value;

    const status =
        statusFilter.value;

    fetch(
        `/services?page=${page}&search=${search}&status=${status}`,
        {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        }
    )
    .then(res => res.text())
    .then(html => {

        document.getElementById(
            'servicesContent'
        ).innerHTML = html;

    });
}

// debounce
function debounceFetch()
{
    clearTimeout(debounceTimer);

    debounceTimer =
        setTimeout(() => {

            fetchServices();

        }, 300);
}

// EVENTS
searchInput.addEventListener(
    'keyup',
    debounceFetch
);

statusFilter.addEventListener(
    'change',
    () => fetchServices()
);

// PAGINATION
document.addEventListener('click', function(e)
{
    if (
        e.target.closest(
            '#paginationWrapper a'
        )
    ) {

        e.preventDefault();

        const url =
            e.target.closest('a').href;

        const page =
            new URL(url)
            .searchParams
            .get('page');

        fetchServices(page);
    }
});

</script>
@endsection