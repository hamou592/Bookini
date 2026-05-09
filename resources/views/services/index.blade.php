@extends('admin.layout')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h2 class="text-3xl font-bold" style="color: var(--text-main);">
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

<div class="bg-white rounded-2xl shadow overflow-hidden">

    <table class="w-full">
        <thead style="background: #F3F4F6;">
            <tr>
                <th class="p-4 text-left">Name</th>
                <th class="p-4 text-left">Provider</th>
                <th class="p-4 text-left">Department</th>
                <th class="p-4 text-left">Price</th>
                <th class="p-4 text-left">Duration</th>
                <th class="p-4 text-left">Status</th>
                <th class="p-4 text-left">Actions</th>
            </tr>
        </thead>

        <tbody>

            @foreach($services as $service)

            <tr class="border-t">

                <td class="p-4 font-medium">
                    {{ $service->name }}
                </td>

                <td class="p-4">
                    {{ $service->provider->name ?? '-' }}
                </td>

                <td class="p-4">
                    {{ $service->department->name ?? '-' }}
                </td>

                <td class="p-4">
                    {{ $service->price }} DA
                </td>

                <td class="p-4">
                    {{ $service->duration }} min
                </td>

                <td class="p-4">

                    @if($service->is_active)

                        <span class="px-3 py-1 rounded-full text-sm bg-green-100 text-green-700">
                            Active
                        </span>

                    @else

                        <span class="px-3 py-1 rounded-full text-sm bg-red-100 text-red-700">
                            Inactive
                        </span>

                    @endif

                </td>

                <td class="p-4 flex gap-2">

                    <a href="/services/{{ $service->id }}/edit"
                       class="px-4 py-2 rounded-lg text-white"
                       style="background: var(--primary);">
                        Edit
                    </a>

                    <form action="/services/{{ $service->id }}"
                          method="POST">

                        @csrf
                        @method('DELETE')

                        <button class="px-4 py-2 rounded-lg bg-red-500 text-white">
                            Delete
                        </button>

                    </form>

                </td>

            </tr>

            @endforeach

        </tbody>

    </table>

</div>

@endsection