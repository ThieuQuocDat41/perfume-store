@extends('layouts.app')
@section('content')

<div class="bg-[#fdf8f8] min-h-screen">

    <main class="max-w-[1440px] mx-auto px-12 pt-24">

        {{-- Header --}}
        <header class="mb-8">
            <h1 class="text-4xl lg:text-5xl font-noto-serif font-semibold text-[#1c1b1b]">Your Cart</h1>
            <p class="mt-3 text-sm text-[#1c1b1b] font-manrope">Mua gì ít dậy, mua mạnh lên đi mấy ní! </p>
        </header>

        @if(session('status'))
            <div class="mb-6 p-3 bg-green-100 text-green-800 rounded">
                {{ session('status') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-3 bg-red-100 text-red-800 rounded">
                {{ session('error') }}
            </div>
        @endif

        @if($cart && $cartItems->count())

        <form method="POST" action="{{ route('checkout') }}">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">

                {{-- Items (8/12) --}}
                <div class="lg:col-span-8">

                    @foreach($cartItems as $item)

                    @php $priceVal = $item->product->price_usd ?? ($item->product->price ?? 0); @endphp
                    <div class="relative bg-white p-6 rounded-lg shadow-sm mb-6" data-price="{{ $priceVal }}" data-quantity="{{ $item->quantity }}" data-item-id="{{ $item->id }}">
            

                        <div class="flex gap-6 items-start">

                            <div class="flex-shrink-0 mt-2">
                                <input type="checkbox" name="selected[]" value="{{ $item->id }}" checked class="appearance-none h-5 w-5 border border-gray-300 rounded-md checked:bg-[#d4af37] checked:border-[#d4af37] transition-all" />
                            </div>
                            

                            <div class="w-32 lg:w-40 aspect-[4/5] overflow-hidden rounded-lg">
@php
    $imgs = is_array($item->product->images)
        ? $item->product->images
        : (json_decode($item->product->images, true) ?: []);

    $cartImg = $imgs[0] ?? asset('images/placeholder.png');

    if (!Illuminate\Support\Str::startsWith($cartImg, ['http://', 'https://'])) {
        $cartImg = asset('storage/' . ltrim($cartImg, '/'));
    }
@endphp

<img
    src="{{ $cartImg }}"
    class="w-full h-full object-cover"
    alt="{{ $item->product->name }}"
>
                            </div>

                            <div class="flex-1 flex flex-col justify-between">

                                <div>
                                    <span class="inline-block text-xs text-[#d4af37] font-manrope tracking-[0.2em] uppercase">Parfum de Nuit</span>
                                    <h3 class="mt-2 text-xl font-noto-serif text-[#1c1b1b]">{{ $item->product->name }}</h3>
                                    <p class="mt-1 text-sm text-gray-600 font-manrope">Volume: {{ $item->volume ?? '50ml' }}</p>
                                </div>

                                <div class="flex items-center justify-between mt-4">

<div class="inline-flex items-center border rounded-md overflow-hidden">

    {{-- DECREASE --}}
    <button
        type="button"
        onclick="updateQuantity({{ $item->id }}, 'decrease')"
        class="px-3 py-2 text-gray-600 hover:bg-gray-100 transition"
    >
        <span class="material-symbols-outlined">remove</span>
    </button>

    {{-- QUANTITY --}}
    <div class="px-4 text-sm min-w-[40px] text-center">
        {{ $item->quantity }}
    </div>

    {{-- INCREASE --}}
    <button
        type="button"
        onclick="updateQuantity({{ $item->id }}, 'increase')"
        class="px-3 py-2 text-gray-600 hover:bg-gray-100 transition"
    >
        <span class="material-symbols-outlined">add</span>
    </button>

</div>

                                            <div class="text-right text-lg font-noto-serif text-[#1c1b1b]">
<div class="flex flex-col items-end gap-4">

  
<a
    href="{{ route('cart.remove', $item->id) }}"
    onclick="event.preventDefault(); deleteCartItem({{ $item->id }})"
    class="group p-2 rounded-full transition-all duration-200 hover:bg-red-50 cursor-pointer"
>

    <svg xmlns="http://www.w3.org/2000/svg"
         class="w-5 h-5 text-gray-400 transition-all duration-200
                group-hover:text-red-500"
         fill="none"
         viewBox="0 0 24 24"
         stroke="currentColor"
         stroke-width="2">

        <path stroke-linecap="round"
              stroke-linejoin="round"
              d="M6 7h12M9 7V4h6v3m-7 4v6m4-6v6m4-6v6M5 7l1 13h12l1-13"/>

    </svg>

</a>

    {{-- PRICE --}}
    <div class="text-right text-lg font-noto-serif text-[#1c1b1b]">
        ${{ number_format(($item->product->price_usd ?? ($item->product->price ?? 0)) * $item->quantity, 2) }}
    </div>

</div>                                            </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    @endforeach

                </div>

                {{-- Summary (4/12) --}}
                <aside class="lg:col-span-4">
                    <div class="sticky top-40">
                        <div class="bg-surface-container p-6 rounded-lg shadow-[0_20px_40px_rgba(0,0,0,0.04)]">
                            <h2 class="text-lg font-noto-serif mb-4">Order Summary</h2>

                            <div class="flex justify-between text-sm mb-2 font-manrope">
                                <span>Subtotal</span>
                                <span id="summary-subtotal" class="font-noto-serif">${{ number_format($total, 2) }}</span>
                            </div>

                            <div class="flex justify-between text-sm mb-2 font-manrope">
                                <span>Shipping</span>
                                <span class="text-[#d4af37]">Complimentary</span>
                            </div>

                            <div class="flex justify-between text-sm mb-4 font-manrope">
                                <span>Estimated Tax</span>
                                <span>$0.00</span>
                            </div>

                            <hr class="my-4">

                            <div class="flex justify-between items-end">
                                <div>
                                    <p class="text-sm text-gray-600">Grand Total</p>
                                    <p class="text-3xl font-noto-serif"> <span id="summary-total">${{ number_format($total, 2) }}</span></p>
                                </div>
                            </div>

                            <button type="submit" class="mt-6 w-full bg-[#d4af37] text-[#1c1b1b] font-manrope tracking-[0.2em] py-3 rounded shadow-md hover:-translate-y-1 transition-all">CHECKOUT</button>

                        </div>
                    </div>
                </aside>

            </div>

        </form>

        @else

        <p class="text-gray-500">Your cart is empty.</p>

        @endif

    </main>

</div>
<script>
    function deleteCartItem(id) {

        const form = document.createElement('form');

        form.method = 'POST';
        form.action = `/cart/remove/${id}`;

        form.innerHTML = `
            @csrf
            <input type="hidden" name="_method" value="DELETE">
        `;

        document.body.appendChild(form);

        form.submit();
    }
</script>
<script>

    function updateQuantity(id, action) {

        const form = document.createElement('form');

        form.method = 'POST';

        form.action = `/cart/${id}/${action}`;

        form.innerHTML = `
            @csrf
            <input type="hidden" name="_method" value="PATCH">
        `;

        document.body.appendChild(form);

        form.submit();
    }

</script>
@endsection
