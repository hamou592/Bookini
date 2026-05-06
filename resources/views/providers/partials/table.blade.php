<tbody id="providersTable">

@foreach($providers as $provider)
<tr class="border-t hover:bg-gray-50 transition">

    <td class="p-4">
        @if($provider->logo)
            <img src="{{ asset('storage/'.$provider->logo) }}"
                 class="w-10 h-10 rounded-full object-cover cursor-pointer"
                 onclick="openImage('{{ asset('storage/'.$provider->logo) }}')">
        @else
            <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-xs">
                N/A
            </div>
        @endif
    </td>

    <td class="p-4 font-medium">{{ $provider->name }}</td>

    <td class="p-4">
        <span class="px-3 py-1 rounded-full text-xs bg-indigo-100 text-indigo-600">
            {{ ucfirst($provider->type) }}
        </span>
    </td>

    <td class="p-4">{{ $provider->phone ?? '-' }}</td>

    <td class="p-4">
        <span class="px-3 py-1 rounded-full text-xs
            {{ $provider->subscription_status == 'active' ? 'bg-green-100 text-green-600' : 'bg-gray-200 text-gray-600' }}">
            {{ ucfirst($provider->subscription_status) }}
        </span>
    </td>

    <td class="p-4">
        {{ $provider->subscription_start_at 
            ? \Carbon\Carbon::parse($provider->subscription_start_at)->format('Y-m-d H:i')
            : '-' }}
    </td>

    <td class="p-4">
        {{ $provider->subscription_start_at 
            ? \Carbon\Carbon::parse($provider->subscription_start_at)->addDays(30)->format('Y-m-d H:i')
            : '-' }}
    </td>

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