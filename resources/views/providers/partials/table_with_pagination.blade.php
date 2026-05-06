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

    <tbody class="text-gray-700">

        @foreach($providers as $provider)
        <tr class="border-t hover:bg-gray-50 transition">

            <!-- LOGO -->
            <td class="p-4">
                @if($provider->logo)
                    <img src="{{ asset('storage/'.$provider->logo) }}"
                         class="w-10 h-10 rounded-full object-cover">
                @else
                    <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-xs">
                        N/A
                    </div>
                @endif
            </td>

            <!-- NAME -->
            <td class="p-4 font-medium">
                {{ $provider->name }}
            </td>

            <!-- TYPE -->
            <td class="p-4">
                <span class="px-3 py-1 rounded-full text-xs font-medium
                    bg-indigo-100 text-indigo-600">
                    {{ ucfirst($provider->type) }}
                </span>
            </td>

            <!-- PHONE -->
            <td class="p-4">
                {{ $provider->phone ?? '-' }}
            </td>

            <!-- STATUS -->
            <td class="p-4">
                <span class="px-3 py-1 rounded-full text-xs font-medium
                    {{ $provider->subscription_status == 'active' ? 'bg-green-100 text-green-600' : 'bg-gray-200 text-gray-600' }}">
                    {{ ucfirst($provider->subscription_status) }}
                </span>
            </td>

            <!-- START -->
            <td class="p-4">
                {{ $provider->subscription_start_at 
                    ? \Carbon\Carbon::parse($provider->subscription_start_at)->format('Y-m-d H:i')
                    : '-' }}
            </td>

            <!-- EXPIRES -->
            <td class="p-4">
                {{ $provider->subscription_start_at 
                    ? \Carbon\Carbon::parse($provider->subscription_start_at)->addDays(30)->format('Y-m-d H:i')
                    : '-' }}
            </td>

            <!-- ACTIONS -->
            <td class="p-4 text-right">
                <div class="inline-flex gap-2">

                    <a href="/providers/{{ $provider->id }}/edit"
                       class="bg-indigo-500 text-white px-3 py-1 rounded-lg text-sm">
                        Edit
                    </a>

                    <form method="POST" action="/providers/{{ $provider->id }}">
                        @csrf
                        @method('DELETE')

                        <button class="bg-red-500 text-white px-3 py-1 rounded-lg text-sm">
                            Delete
                        </button>
                    </form>

                </div>
            </td>

        </tr>
        @endforeach

    </tbody>

</table>

<!-- PAGINATION -->
<div class="mt-6">
    {{ $providers->links() }}
</div>