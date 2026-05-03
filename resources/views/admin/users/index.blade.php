@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-semibold mb-4">Users</h1>

@if(session('status'))
    <div class="mb-4 p-3 bg-green-100 text-green-800">{{ session('status') }}</div>
@endif

<table class="w-full bg-white rounded shadow">
    <thead class="bg-gray-100">
        <tr>
            <th class="p-3 text-left">ID</th>
            <th class="p-3 text-left">Name</th>
            <th class="p-3 text-left">Email</th>
            <th class="p-3 text-left">Role</th>
            <th class="p-3 text-left">Actions</th>
        </tr>
    </thead>
    <tbody>
    @foreach($users as $u)
        <tr class="border-t">
            <td class="p-3">{{ $u->id }}</td>
            <td class="p-3">{{ $u->name }}</td>
            <td class="p-3">{{ $u->email }}</td>
            <td class="p-3">{{ $u->role ?? 'user' }}</td>
            <td class="p-3">
                <a href="/admin/users/{{ $u->id }}" class="px-3 py-1 bg-blue-500 text-white rounded">Details</a>
                <a href="/admin/users/{{ $u->id }}/edit" class="px-3 py-1 bg-yellow-500 text-white rounded">Edit</a>
                <form action="/admin/users/{{ $u->id }}/delete" method="POST" class="inline" onsubmit="return confirm('Delete user #'+{{ $u->id }}+'?');">
                    @csrf
                    <button class="px-3 py-1 bg-red-500 text-white rounded">Delete</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="mt-4">
    {{ $users->links() }}
</div>

@endsection
