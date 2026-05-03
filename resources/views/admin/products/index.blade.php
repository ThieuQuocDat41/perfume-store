@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-semibold mb-4">Products (Admin)</h1>

@if(session('status'))
    <div class="mb-4 p-3 bg-green-100 text-green-800">{{ session('status') }}</div>
@endif

<table class="w-full bg-white rounded shadow">
    <thead class="bg-gray-100">
        <tr>
            <th class="p-3 text-left">ID</th>
            <th class="p-3 text-left">Name</th>
            <th class="p-3 text-left">Price</th>
            <th class="p-3 text-left">Stock</th>
            <th class="p-3 text-left">Actions</th>
        </tr>
    </thead>
    <tbody>
    @foreach($products as $p)
        <tr class="border-t">
            <td class="p-3">{{ $p->id }}</td>
            <td class="p-3">{{ $p->name }}</td>
            <td class="p-3">{{ $p->price }}</td>
            <td class="p-3">{{ $p->stock }}</td>
            <td class="p-3">
                <a href="/admin/products/{{ $p->id }}/edit" class="px-3 py-1 bg-yellow-500 text-white rounded">Edit</a>
                <form action="/admin/products/{{ $p->id }}" method="POST" class="inline" onsubmit="return confirm('Delete product #'+{{ $p->id }}+'?');">
                    @csrf
                    @method('PATCH')
                </form>
                <form action="/admin/products/{{ $p->id }}/delete" method="POST" class="inline" onsubmit="return confirm('Delete product #'+{{ $p->id }}+'?');">
                    @csrf
                    <button class="px-3 py-1 bg-red-500 text-white rounded">Delete</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="mt-4">
    {{ $products->links() }}
</div>

@endsection
