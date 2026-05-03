@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-semibold mb-4">Edit User</h1>

<form action="/admin/users/{{ $user->id }}" method="POST" class="bg-white p-6 rounded shadow">
    @csrf
    @method('PATCH')

    <div class="mb-4">
        <label class="block mb-1">Name</label>
        <input name="name" value="{{ old('name', $user->name) }}" class="w-full border p-2" />
    </div>

    <div class="mb-4">
        <label class="block mb-1">Email</label>
        <input name="email" value="{{ old('email', $user->email) }}" class="w-full border p-2" />
    </div>

    <div class="mb-4">
        <label class="block mb-1">Phone</label>
        <input name="phone" value="{{ old('phone', $user->phone) }}" class="w-full border p-2" />
    </div>

    <div class="mb-4">
        <label class="block mb-1">Role</label>
        <select name="role" class="w-full border p-2">
            <option value="user" {{ (old('role', $user->role) == 'user') ? 'selected' : '' }}>User</option>
            <option value="admin" {{ (old('role', $user->role) == 'admin') ? 'selected' : '' }}>Admin</option>
        </select>
    </div>

    <div>
        <button class="px-4 py-2 bg-green-600 text-white rounded">Save</button>
        <a href="/admin/users" class="ml-2 px-4 py-2 bg-gray-300 rounded">Cancel</a>
    </div>
</form>

@endsection
