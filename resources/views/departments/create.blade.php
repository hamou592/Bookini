@extends('admin.layout')

@section('content')

<h2>Create Department</h2>

<form method="POST" action="/departments">
    @csrf

    <input name="name" placeholder="Department Name" class="w-full border p-2 mb-3">

    <select name="provider_id" class="w-full border p-2 mb-3">
        @foreach($providers as $p)
            <option value="{{ $p->id }}">{{ $p->name }}</option>
        @endforeach
    </select>

    <button class="bg-indigo-500 text-white px-4 py-2">Create</button>
</form>

@endsection