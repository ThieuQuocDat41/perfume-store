@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-semibold mb-4">Edit Product</h1>

<form action="/admin/products/{{ $product->id }}" method="POST" class="bg-white p-6 rounded shadow">
    @csrf
    @method('PATCH')

    <div class="mb-4">
        <label class="block mb-1">Name</label>
        <input name="name" value="{{ old('name', $product->name) }}" class="w-full border p-2" />
    </div>

    <div class="mb-4">
        <label class="block mb-1">Brand</label>
        <input name="brand" value="{{ old('brand', $product->brand) }}" class="w-full border p-2" />
    </div>

    <div class="mb-4">
        <label class="block mb-1">Price</label>
        <input name="price" value="{{ old('price', $product->price) }}" class="w-full border p-2" />
    </div>

    <div class="mb-4">
        <label class="block mb-1">Stock</label>
        <input name="stock" value="{{ old('stock', $product->stock) }}" class="w-full border p-2" />
    </div>

    <div class="mb-4">
        <label class="block mb-1">Description</label>
        <textarea name="description" class="w-full border p-2">{{ old('description', $product->description) }}</textarea>
    </div>

    <div class="mb-4">
        <label class="block mb-1">Tags (comma separated)</label>
        <input name="tags_raw" value="{{ old('tags_raw', implode(',', (array)$product->tags ?? [])) }}" class="w-full border p-2" />
    </div>

    <div>
        <button class="px-4 py-2 bg-green-600 text-white rounded">Save</button>
        <a href="/admin/products" class="ml-2 px-4 py-2 bg-gray-300 rounded">Cancel</a>
    </div>
</form>

@endsection
