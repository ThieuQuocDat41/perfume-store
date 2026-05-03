@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-semibold mb-4">User Details</h1>

<div class="bg-white p-6 rounded shadow">
    <p><strong>ID:</strong> {{ $user->id }}</p>
    <p><strong>Name:</strong> {{ $user->name }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Phone:</strong> {{ $user->phone }}</p>
    <p><strong>Role:</strong> {{ $user->role }}</p>
    <p class="mt-4">
        <a href="/admin/users/{{ $user->id }}/edit" class="px-3 py-1 bg-yellow-500 text-white rounded">Edit</a>
        <a href="/admin/users" class="px-3 py-1 bg-gray-300 rounded">Back</a>
    </p>
</div>

@endsection
