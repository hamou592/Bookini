<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bookini Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50">

<div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-200 shadow-sm">

        <div class="p-6 border-b">
            <h2 class="text-xl font-bold text-indigo-600">Bookini</h2>
        </div>

        <nav class="p-4 space-y-2">

            {{-- USERS (only super_admin) --}}
            @if(auth()->user()->hasRole('super_admin'))
                <a href="/users"
                   class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition">
                    Users
                </a>
            @endif

            <a href="#"
               class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                Providers
            </a>

            <a href="#"
               class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                Departments
            </a>

            <a href="#"
               class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                Services
            </a>

            <a href="#"
               class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                Appointments
            </a>

        </nav>

        <div class="p-4 border-t mt-auto">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full bg-red-500 text-white py-2 rounded-lg hover:bg-red-600">
                    Logout
                </button>
            </form>
        </div>

    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6">

        <!-- Topbar -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">
                Dashboard
            </h1>

            <span class="text-sm text-gray-600">
                {{ auth()->user()->name }}
            </span>
        </div>

        <!-- Page Content -->
        <div class="bg-white p-6 rounded-2xl shadow border border-gray-200">
            @yield('content')
        </div>

    </main>

</div>

</body>
</html>