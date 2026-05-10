<table class="w-full text-sm">

    <thead class="text-sm"
           style="background-color: #F9FAFB; color: var(--text-secondary);">

        <tr>

            <th class="p-4 text-left">
                Department
            </th>

            {{-- SUPER ADMIN ONLY --}}
            @if(auth()->user()->hasRole('super_admin'))

            <th class="p-4 text-left">
                Provider
            </th>

            @endif

            <th class="p-4 text-right">
                Actions
            </th>

        </tr>

    </thead>

    <tbody class="text-gray-700">

        @forelse($departments as $d)

        <tr class="border-t hover:bg-gray-50 transition">

            {{-- NAME --}}
            <td class="p-4 font-medium">
                {{ $d->name }}
            </td>

            {{-- PROVIDER ONLY FOR SUPER ADMIN --}}
            @if(auth()->user()->hasRole('super_admin'))

            <td class="p-4">

                <span class="px-2 py-1 rounded text-xs bg-gray-100">

                    {{ $d->provider->name ?? '-' }}

                </span>

            </td>

            @endif

            {{-- ACTIONS --}}
            <td class="p-4 text-right">

                <div class="inline-flex gap-2">

                    {{-- EDIT --}}
                    <a href="/departments/{{ $d->id }}/edit"
                       style="background-color: var(--primary);"
                       onmouseover="this.style.backgroundColor='var(--primary-hover)'"
                       onmouseout="this.style.backgroundColor='var(--primary)'"
                       class="text-white px-3 py-1 rounded-lg text-sm">

                        Edit

                    </a>

                    {{-- DELETE --}}
                    <form method="POST"
                          action="/departments/{{ $d->id }}"
                          onsubmit="return confirm('Delete this department?');">

                        @csrf
                        @method('DELETE')

                        <button
                            class="bg-red-500 text-white px-3 py-1 rounded-lg text-sm hover:bg-red-600">

                            Delete

                        </button>

                    </form>

                </div>

            </td>

        </tr>

        @empty

        <tr>

            <td colspan="{{ auth()->user()->hasRole('super_admin') ? 3 : 2 }}"
                class="p-6 text-center text-gray-400">

                No departments found

            </td>

        </tr>

        @endforelse

    </tbody>

</table>

{{-- PAGINATION --}}
<div id="paginationWrapper" class="p-4">
    {{ $departments->links() }}
</div>