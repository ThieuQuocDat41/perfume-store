@extends('layouts.app')

@section('content')

<div class="max-w-[1440px] mx-auto px-10 py-16">

    <!-- GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">

<!-- LEFT: IMAGE -->
<div class="lg:col-span-7">

    <!-- MAIN IMAGE -->
    <div class="relative">
        <img 
            src="{{ $product->image ? asset('storage/products/' . urlencode($product->image)) : asset('storage/products/hero.jpeg') }}"
            class="w-full h-full object-cover"
        >

        @if(auth()->check() && auth()->user()->role === 'admin')
            <form id="image-upload-form" action="{{ url('/products/'.$product->id.'/image') }}" method="POST" enctype="multipart/form-data" class="hidden">
                @csrf
                <input type="file" name="image" id="product-image-input" accept="image/*">
            </form>

            <button id="edit-image-btn" type="button" class="absolute top-2 right-2 bg-white rounded-full p-2 shadow" title="Edit image">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6-6 3 3-6 6H9v-3z" /></svg>
            </button>
        @endif
    </div>
    <!-- SUB IMAGES -->
    <div class="grid grid-cols-2 gap-4 mt-4">
        <div class="relative">
            <img 
                src="{{ $product->sub_image_1 ? asset('storage/products/' . urlencode($product->sub_image_1)) : ($product->image ? asset('storage/products/' . urlencode($product->image)) : asset('storage/products/hero.jpeg')) }}"
                class="rounded-lg object-cover w-full h-40"
            >

            @if(auth()->check() && auth()->user()->role === 'admin')
                <form id="sub1-upload-form" action="{{ url('/products/'.$product->id.'/image') }}" method="POST" enctype="multipart/form-data" class="hidden">
                    @csrf
                    <input type="hidden" name="slot" value="sub1">
                    <input type="file" name="image" id="product-sub1-input" accept="image/*">
                </form>

                <button data-slot="sub1" class="edit-sub-btn absolute top-2 right-2 bg-white rounded-full p-2 shadow" title="Edit image">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6-6 3 3-6 6H9v-3z" /></svg>
                </button>
            @endif
        </div>

        <div class="relative">
            <img 
                src="{{ $product->sub_image_2 ? asset('storage/products/' . urlencode($product->sub_image_2)) : ($product->image ? asset('storage/products/' . urlencode($product->image)) : asset('storage/products/hero.jpeg')) }}"
                class="rounded-lg object-cover w-full h-40"
            >

            @if(auth()->check() && auth()->user()->role === 'admin')
                <form id="sub2-upload-form" action="{{ url('/products/'.$product->id.'/image') }}" method="POST" enctype="multipart/form-data" class="hidden">
                    @csrf
                    <input type="hidden" name="slot" value="sub2">
                    <input type="file" name="image" id="product-sub2-input" accept="image/*">
                </form>

                <button data-slot="sub2" class="edit-sub-btn absolute top-2 right-2 bg-white rounded-full p-2 shadow" title="Edit image">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6-6 3 3-6 6H9v-3z" /></svg>
                </button>
            @endif
        </div>
    </div>

</div>

        <!-- RIGHT: INFO -->
        <div class="lg:col-span-5 space-y-8">

            <div>
                <p class="uppercase tracking-widest text-gray-500 text-xs">
                    {{ $product->brand ?? 'Luxury Brand' }}
                </p>

                <h1 class="text-4xl font-serif mt-2">
                    {{ $product->name }}
                </h1>

                <p class="text-2xl text-yellow-600 font-serif mt-2">
                    ${{ $product->price }}
                </p>

                <p class="text-green-600 text-sm mt-2">
                    ✔ Còn hàng ({{ $product->stock }})
                </p>
            </div>

            <p class="text-gray-600">
                {{ $product->description }}
            </p>

            <!-- TAG -->
            <div>
                @php
                    $allTags = ['Floral','Woody','Luxury'];
                    $productTags = is_array($product->tags) ? $product->tags : (json_decode($product->tags, true) ?: []);
                @endphp

                @if(auth()->check() && auth()->user()->role === 'admin')
                    <form action="{{ url('/products/'.$product->id.'/tags') }}" method="POST">
                        @csrf
                        <div class="flex gap-2 flex-wrap">
                            @foreach($allTags as $t)
                                <label class="inline-flex items-center px-3 py-1 bg-gray-100 rounded-full text-sm cursor-pointer">
                                    <input type="checkbox" name="tags[]" value="{{ $t }}" class="mr-2" {{ in_array($t, $productTags) ? 'checked' : '' }}>
                                    {{ $t }}
                                </label>
                            @endforeach
                        </div>
                        <button class="mt-2 px-3 py-1 bg-yellow-500 text-white text-sm">Save tags</button>
                    </form>
                @else
                    <div class="flex gap-2 flex-wrap">
                        @foreach($productTags as $t)
                            <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">{{ $t }}</span>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- VOLUME -->
            <div>
                <p class="text-sm uppercase tracking-widest mb-2">Dung tích</p>

                <form action="/cart/add/{{ $product->id }}" method="POST">
                    @csrf
                    <div class="flex gap-3 items-center">
                        <label class="inline-flex items-center">
                            <input type="radio" name="volume" value="50ML" class="mr-2" {{ old('volume') == '50ML' ? 'checked' : '' }}>
                            50ML
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="volume" value="100ML" class="mr-2" {{ old('volume', '100ML') == '100ML' ? 'checked' : '' }}>
                            100ML
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="volume" value="200ML" class="mr-2" {{ old('volume') == '200ML' ? 'checked' : '' }}>
                            200ML
                        </label>
                    </div>

                    <!-- ADD TO CART -->
                    <div class="mt-4">
                        <button class="w-full py-4 bg-yellow-500 text-white uppercase tracking-widest hover:bg-yellow-600 transition">
                            Add to Cart
                        </button>
                    </div>
                </form>
            </div>

            <!-- WISHLIST -->


        </div>
    </div>

    <!-- OLFACTORY -->
    <div class="mt-24">
        <h2 class="text-3xl font-serif text-center mb-10">
            Olfactory Profile
        </h2>

        <div class="grid md:grid-cols-3 gap-6">

            <div class="p-6 bg-gray-100 rounded-lg text-center">
                <h3 class="font-serif italic">Top Notes</h3>
                <p class="text-sm text-gray-600">Bergamot, Citrus</p>
            </div>

            <div class="p-6 bg-gray-100 rounded-lg text-center">
                <h3 class="font-serif italic">Heart Notes</h3>
                <p class="text-sm text-gray-600">Rose, Jasmine</p>
            </div>

            <div class="p-6 bg-gray-100 rounded-lg text-center">
                <h3 class="font-serif italic">Base Notes</h3>
                <p class="text-sm text-gray-600">Musk, Vanilla</p>
            </div>

        </div>
    </div>

    <!-- RELATED -->
    <div class="mt-24">
        <h2 class="text-2xl font-serif mb-6">
            You May Also Like
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

            @foreach($related as $item)

            <div class="group">
                <a href="/products/{{ $item->id }}">
                    <div class="aspect-[3/4] overflow-hidden rounded-lg">
                        <img 
                            src="{{ $item->image ? asset('storage/products/' . urlencode($item->image)) : asset('storage/products/hero.jpeg') }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition"
                        >
                    </div>

                    <p class="mt-2 font-serif">
                        {{ $item->name }}
                    </p>

                    <p class="text-yellow-600">
                        ${{ $item->price }}
                    </p>
                </a>
            </div>

            @endforeach

        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const btn = document.getElementById('edit-image-btn');
    const input = document.getElementById('product-image-input');
    const form = document.getElementById('image-upload-form');
    if(btn && input){
        btn.addEventListener('click', function(){ input.click(); });
        input.addEventListener('change', function(){
            if(input.files.length){ form.submit(); }
        });
    }
    // sub images
    document.querySelectorAll('.edit-sub-btn').forEach(function(b){
        b.addEventListener('click', function(){
            const slot = b.getAttribute('data-slot');
            const input = document.getElementById('product-' + slot + '-input');
            const form = document.getElementById(slot + '-upload-form');
            if(input){ input.click(); }
            if(input){
                input.addEventListener('change', function(){ if(input.files.length){ form.submit(); } });
            }
        });
    });
});
</script>

@endsection