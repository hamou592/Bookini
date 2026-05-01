<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Unauthorized</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-200 text-center max-w-md">

        <h1 class="text-3xl font-bold text-red-500 mb-4">
            403
        </h1>

        <h2 class="text-xl font-semibold text-gray-800 mb-2">
            Access Denied
        </h2>

        <p class="text-gray-600 mb-6">
            You are not authorized to access this page.
        </p>

        <a href="/dashboard"
           class="inline-block bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
            Back to Dashboard
        </a>

    </div>

</body>
</html>