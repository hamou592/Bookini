@extends('admin.layout')

@section('content')
    <h2 class="text-xl font-semibold text-gray-800 mb-4">
        Welcome, {{ auth()->user()->name }}
    </h2>

    <p class="text-gray-600">
        This is your dashboard. Manage your system from the sidebar.
    </p>
@endsection