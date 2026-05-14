<table class="w-full">

    <thead style="background: #F3F4F6;">

        <tr>

            <th class="p-4 text-left">
                Name
            </th>

            @if($isSuperAdmin)
                <th class="p-4 text-left">
                    Provider
                </th>
            @endif

            @if($isSuperAdmin || $isClinicProvider)
                <th class="p-4 text-left">
                    Department
                </th>
            @endif

            <th class="p-4 text-left">
                Price
            </th>

            <th class="p-4 text-left">
                Duration
            </th>

            <th class="p-4 text-left">
                Status
            </th>

            <th class="p-4 text-left">
                Actions
            </th>

        </tr>

    </thead>

    <tbody>

        @forelse($services as $service)

            <tr class="border-t">

                <td class="p-4 font-medium">
                    {{ $service->name }}
                </td>

                @if($isSuperAdmin)

                    <td class="p-4">
                        {{ $service->provider->name ?? '-' }}
                    </td>

                @endif

                @if($isSuperAdmin || $isClinicProvider)

                    <td class="p-4">
                        {{ $service->department->name ?? '-' }}
                    </td>

                @endif

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
                          method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this service?');">

                        @csrf
                        @method('DELETE')

                        <button class="px-4 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600 transition">

                            Delete

                        </button>

                    </form>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="7"
                    class="p-6 text-center text-gray-500">

                    No services found.

                </td>

            </tr>

        @endforelse

    </tbody>

</table>

<div id="paginationWrapper" class="p-4">
    {{ $services->links() }}
</div>