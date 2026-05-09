@extends('admin.layout')

@section('content')

<h2>Edit Department</h2>

<form method="POST" action="/departments/{{ $department->id }}">
    @csrf
    @method('PUT')

    <input name="name" value="{{ $department->name }}" class="w-full border p-2 mb-3">

    <select name="provider_id" class="w-full border p-2 mb-3">
        @foreach($providers as $p)
            <option value="{{ $p->id }}"
                {{ $department->provider_id == $p->id ? 'selected' : '' }}>
                {{ $p->name }}
            </option>
        @endforeach
    </select>

    <button class="bg-indigo-500 text-white px-4 py-2">Update</button>
</form>

@endsection