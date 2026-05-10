@extends('layouts.app')

@section('content')

<!-- HERO -->
<section class="relative h-[700px]">
    <img src="/images/hero.jpeg"
        class="absolute w-full h-full object-cover">

    <div class="absolute inset-0 bg-black/40"></div>

    <div class="relative z-10 h-full flex items-center px-20">
        <div class="text-white max-w-xl">
            <p class="uppercase tracking-[0.3em] text-sm text-yellow-400">
                Khi mùi hương trở thành nghệ thuật
            </p>

            <h1 class="text-5xl font-serif mt-4">
                Discover Your Signature
            </h1>

            <p class="mt-6 text-gray-200">
                Mỗi mùi hương là một câu chuyện chưa kể, <br>
                Hãy cùng TTRINHPERFUME tìm ra câu chuyện của riêng bạn.
            </p>

<a href="#self"
   class="inline-block mt-6 px-8 py-3 bg-yellow-500 text-black rounded
          hover:scale-105 transition duration-300">
    Shop Now
</a>
        </div>
    </div>
</section>

<!-- COLLECTION -->
<section class="px-20 mt-20 grid grid-cols-12 gap-6">

    <!-- BIG -->
    <div class="col-span-8 relative group overflow-hidden rounded-lg">
        <img src="/images/floral.jpg"
             class="w-full h-[400px] object-cover transition duration-500 group-hover:scale-110">

        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>

        <div class="absolute bottom-5 left-5 text-white">
            <h3 class="text-2xl font-serif">Floral Collections</h3>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="col-span-4 flex flex-col gap-6">

        <!-- WOODY -->
        <div class="relative group overflow-hidden rounded-lg">
                  <img src="/images/woody.jpg"
                 class="h-[190px] w-full object-cover transition duration-500 group-hover:scale-110">

            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>

            <div class="absolute bottom-3 left-3 text-white">
                <h3 class="text-xl font-serif">Woody Essence</h3>
            </div>
        </div>

        <!-- ORIENTAL -->
        <div class="relative group overflow-hidden rounded-lg">
                  <img src="/images/oriental.jpg"
                 class="h-[190px] w-full object-cover transition duration-500 group-hover:scale-110">

            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>

            <div class="absolute bottom-3 left-3 text-white">
                <h3 class="text-xl font-serif">Oriental Collection</h3>
            </div>
        </div>

    </div>

</section>

<!-- PRODUCTS -->
<section id="self" class="px-20 mt-20">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-10">

        <div>
            <h2 class="text-3xl font-serif">
                Kệ Hàng
            </h2>

            <p class="text-gray-500 italic text-sm">
                Tất cả sản phẩm đều có sẵn, chỉ chờ bạn mang về nhà.
            </p>
        </div>

        <!-- ADD PRODUCT BUTTON -->
        @if(auth()->check() && auth()->user()->role === 'admin')

        <a href="/products/create"
           class="w-12 h-12 rounded-full border-2 border-yellow-500
                  text-yellow-500 text-3xl flex items-center justify-center
                  hover:bg-yellow-500 hover:text-white
                  transition duration-300">

            +

        </a>

        @endif

    </div>

    <!-- PRODUCTS GRID -->
    <div class="bg-[#fdf8f8] px-20 py-16">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

            @foreach($products as $product)

                @php
                    $imgs = is_array($product->images)
                        ? $product->images
                        : (json_decode($product->images, true) ?: []);

                    $firstImg = $imgs[0] ?? ($product->image ?? null);
                @endphp

                @continue(empty($firstImg) || ($product->stock ?? 0) <= 0)

                <div class="group bg-white p-4 rounded-xl border border-neutral-100
                    shadow-[0_10px_30px_rgba(0,0,0,0.02)]
                    hover:shadow-lg hover:-translate-y-2 transition-all duration-300">

                    <!-- IMAGE -->
                    <div class="relative aspect-[3/4] overflow-hidden rounded-lg">

                        <img
                            src="{{ $firstImg ? (Illuminate\Support\Str::startsWith($firstImg, ['http://','https://']) ? $firstImg : asset('storage/products/' . urlencode($firstImg))) : asset('images/placeholder.png') }}"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                            alt="{{ $product->name }}"
                        >

                        <!-- OVERLAY -->
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition duration-300"></div>

                        <!-- ADMIN ACTIONS -->
                        @if(auth()->check() && auth()->user()->role === 'admin')

                        <!-- DELETE -->
                        <form action="/admin/products/{{ $product->id }}/delete"
                              method="POST"
                              class="absolute top-2 left-2 opacity-0 group-hover:opacity-100 transition duration-300 z-10">

                            @csrf

                            <button
                                onclick="return confirm('Xóa sản phẩm này?')"
                                class="bg-white/90 p-2 rounded-full shadow hover:bg-red-500 hover:text-white transition">

                                <span class="material-symbols-outlined text-[18px]">
                                    delete
                                </span>

                            </button>

                        </form>

                        <!-- EDIT -->
                        <a href="/products/{{ $product->id }}/edit"
                           class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition duration-300 z-10">

                            <div class="bg-white/90 p-2 rounded-full shadow hover:bg-black hover:text-white transition">

                                <span class="material-symbols-outlined text-[18px]">
                                    edit
                                </span>

                            </div>

                        </a>

                        @endif

                    </div>

                    <!-- INFO -->
                    <div class="mt-4 text-center">

                        <!-- PRODUCT NAME -->
                        <h3 class="text-xl font-serif font-medium text-black">
                            {{ $product->name }}
                        </h3>

                        <!-- BRAND -->
                        <p class="text-sm italic text-neutral-500 mt-1">
                            {{ $product->brand ?? 'TTrinh Perfume' }}
                        </p>

                        <!-- PRICE -->
                        <div class="flex justify-between items-center mt-3">

                            <span class="text-lg font-serif font-bold">
                                ${{ number_format($product->price_usd ?? $product->price ?? 0, 2) }}
                            </span>

                        </div>

                    </div>

                    <!-- ACTION -->
                    <div class="flex gap-2 mt-4">

                        <!-- DETAILS -->
                        <a href="/products/{{ $product->id }}"
                           class="flex-1 border border-neutral-200 text-center py-2 text-sm
                                  hover:bg-black hover:text-white transition">

                            Details

                        </a>

                        <!-- ADD TO CART -->
                        <form action="/cart/add/{{ $product->id }}"
                              method="POST"
                              class="ajax-add-to-cart">

                            @csrf

                            <button class="w-10 bg-black text-white hover:opacity-80 transition">
                                +
                            </button>

                        </form>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</section>


<footer class="bg-white pt-24 pb-12 px-6 lg:px-20 border-t border-neutral-100">
    
    <div class="max-w-7xl mx-auto">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-16 mb-24">
            <div class="flex items-start gap-6">
                <span class="material-symbols-outlined text-4xl font-light text-neutral-400">credit_card</span>
                <div>
                    <h3 class="text-lg font-arial font-medium lowercase mb-3">exclusive loyalty membership</h3>
                    <p class="text-sm text-neutral-500 leading-relaxed font-light italic">
                        On every order, Gold members receive a 5% discount and Silver members receive a 3% discount. Additional 2% off during your birthday month.
                    </p>
                </div>
            </div>

            <div class="flex items-start gap-6">
                <span class="material-symbols-outlined text-4xl font-light text-neutral-400">local_shipping</span>
                <div>
                    <h3 class="text-lg font-arial font-medium lowercase mb-3">nationwide free shipping</h3>
                    <p class="text-sm text-neutral-500 leading-relaxed font-light italic">
                 ttrinh offers complimentary shipping to all customers nationwide. we are committed to fast and secure delivery.
                    </p>
                </div>
            </div>

            <div class="flex items-start gap-6">
                <span class="material-symbols-outlined text-4xl font-light text-neutral-400">verified</span>
                <div>
                    <h3 class="text-lg font-arial font-medium lowercase mb-3">authentic products</h3>
                    <p class="text-sm text-neutral-500 leading-relaxed font-light italic">
                        Our fragrances are purchased directly from stores abroad or worked with the brands directly, guaranteeing 100% authenticity.
                    </p>
                </div>
            </div>
        </div>

        <hr class="border-neutral-100 mb-50 gap-3">

    </div>
</footer>