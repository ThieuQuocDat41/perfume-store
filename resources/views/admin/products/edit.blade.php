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
        <input name="price_usd" value="{{ old('price_usd', $product->price_usd) }}" class="w-full border p-2" />
    </div>

    <div class="mb-4">
        <label class="block mb-1">Stock</label>
        <input name="stock" value="{{ old('stock', $product->stock) }}" class="w-full border p-2" />
    </div>

    <div class="mb-4">
        <label class="block mb-1">Short Description</label>
        <textarea name="short_description" class="w-full border p-2">{{ old('short_description', $product->short_description) }}</textarea>
    </div>

    <div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block mb-1">Top Notes</label>
            <input name="top_notes" value="{{ old('top_notes', $product->top_notes) }}" class="w-full border p-2" />
        </div>
        <div>
            <label class="block mb-1">Heart Notes</label>
            <input name="heart_notes" value="{{ old('heart_notes', $product->heart_notes) }}" class="w-full border p-2" />
        </div>
        <div>
            <label class="block mb-1">Base Notes</label>
            <input name="base_notes" value="{{ old('base_notes', $product->base_notes) }}" class="w-full border p-2" />
        </div>
    </div>

    <div class="mb-4">
        <label class="block mb-1">Gender</label>
        <select name="gender" class="w-full border p-2">
            <option value="male" {{ old('gender', $product->gender) == 'male' ? 'selected' : '' }}>Male</option>
            <option value="female" {{ old('gender', $product->gender) == 'female' ? 'selected' : '' }}>Female</option>
            <option value="unisex" {{ old('gender', $product->gender) == 'unisex' ? 'selected' : '' }}>Unisex</option>
        </select>
    </div>

    <div class="mb-4">
        <label class="block mb-1">Images (comma separated URLs)</label>
        <div class="mb-2 grid grid-cols-3 gap-2">
            @php $imgs = is_array($product->images) ? $product->images : (json_decode($product->images, true) ?: []); @endphp
            @foreach($imgs as $img)
                <div class="relative">
                    <img src="{{ Illuminate\Support\Str::startsWith($img, ['http://','https://']) ? $img : asset('storage/products/' . urlencode($img)) }}" class="w-full h-24 object-cover rounded" />
                    <label class="absolute top-1 left-1 bg-white/80 px-2 py-1 text-xs rounded">
                        <input type="checkbox" name="remove_images[]" value="{{ $img }}"> Remove
                    </label>
                </div>
            @endforeach
        </div>

        <input type="text" name="images_raw" value="{{ old('images_raw', '') }}" placeholder="Or enter comma separated URLs" class="w-full border p-2 mb-2" />
        <label class="block mb-1">Or upload images</label>
        <input type="file" name="images[]" multiple accept="image/*" class="w-full" />
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
