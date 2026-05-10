@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto px-6 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">

        {{-- Gallery: Single Main Image --}}
        <div class="lg:col-span-4">
            @php
                $imgs = is_array($product->images)
                    ? $product->images
                    : (json_decode($product->images, true) ?: []);
                $mainImg = $imgs[0] ?? asset('images/placeholder.png');
            @endphp

            <div class="overflow-hidden rounded-lg bg-gray-50 aspect-[4/5] group cursor-zoom-in">
                <img
                    src="{{ $mainImg }}"
                    alt="{{ $product->name }}"
                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                >
            </div>
        </div>

        {{-- Product Info --}}
        <div class="lg:col-span-5 space-y-6">

            {{-- Brand --}}
            <p class="uppercase tracking-widest text-gray-400 text-xs font-semibold">
                {{ $product->brand ?? 'Maison de Élégance' }}
            </p>

            {{-- Name --}}
            <h1 class="text-4xl font-serif leading-tight">{{ $product->name }}</h1>

            {{-- Price & Stock --}}
            <div class="flex items-center gap-4">
                <div class="text-3xl text-yellow-600 font-serif">
                    ${{ number_format($product->price_usd ?? 0, 2) }}
                </div>
                <span class="px-3 py-1 bg-green-50 text-green-700 border border-green-200 rounded-full text-xs font-medium flex items-center gap-1">
                    In Stock &amp; Ready to Ship
                </span>
            </div>

            {{-- Description --}}
            <div class="text-gray-600 leading-relaxed text-sm">
                {!! nl2br(e($product->short_description)) !!}
            </div>

            {{-- Scent Tags --}}
            @if($product->top_notes || $product->heart_notes || $product->base_notes)
            <div class="flex flex-wrap gap-2">
                @foreach(array_filter(explode(',', $product->top_notes ?? '')) as $tag)
                    <span class="px-3 py-1 rounded-full border border-gray-300 text-xs text-gray-600">{{ trim($tag) }}</span>
                @endforeach
                @foreach(array_filter(explode(',', $product->heart_notes ?? '')) as $tag)
                    <span class="px-3 py-1 rounded-full border border-gray-300 text-xs text-gray-600">{{ trim($tag) }}</span>
                @endforeach
                @foreach(array_filter(explode(',', $product->base_notes ?? '')) as $tag)
                    <span class="px-3 py-1 rounded-full border border-gray-300 text-xs text-gray-600">{{ trim($tag) }}</span>
                @endforeach
            </div>
            @endif

            {{-- Volume Selector --}}
            <div>
                <p class="uppercase tracking-widest text-xs text-gray-400 font-semibold mb-3">Select Volume</p>
                <div class="flex gap-3" id="volume-selector">
                    @php
                        $volumes = ['30ml', '50ml', '100ml', '200ml'];
                    @endphp
                    @foreach($volumes as $vol)
                        <button
                            type="button"
                            onclick="selectVolume(this)"
                            data-volume="{{ $vol }}"
                            class="volume-btn px-4 py-2 rounded-full border text-sm font-medium transition-all duration-200
                                   border-gray-300 text-gray-600 hover:border-yellow-500 hover:text-yellow-600
                                   {{ $loop->index === 1 ? 'selected !border-yellow-500 !bg-yellow-500 !text-white' : '' }}"
                        >
                            {{ $vol }}
                        </button>
                    @endforeach
                </div>
                <input type="hidden" name="volume" id="selected-volume" value="50ml">
            </div>

            {{-- Add to Cart Form --}}
            <form action="/cart/add/{{ $product->id }}" method="POST" class="space-y-3 pt-2">
                @csrf
                <input type="hidden" name="volume" id="form-volume" value="50ml">

                <button
                    type="submit"
                    class="w-full py-4 rounded-full bg-yellow-500 text-white font-semibold uppercase tracking-widest text-sm
                           flex items-center justify-center gap-2 shadow-md
                           hover:bg-yellow-600 active:scale-[0.98] transition-all duration-200"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Add to Cart
                </button>
            </form>

            {{-- Trust Badges --}}
            <div class="grid grid-cols-2 gap-4 pt-2">
                <div class="flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8"/>
                    </svg>
                    <div>
                        <span class="block text-xs font-bold uppercase tracking-wider mb-0.5">Free Delivery</span>
                        <span class="text-xs text-gray-400">Complimentary 2-day shipping on all orders.</span>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <div>
                        <span class="block text-xs font-bold uppercase tracking-wider mb-0.5">Authentic</span>
                        <span class="text-xs text-gray-400">Directly from our artisan distillery.</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

{{-- SCENT PROFILE --}}
@if(
    $product->scent_tone ||
    $product->top_notes ||
    $product->heart_notes ||
    $product->base_notes
)

<div class="mt-24 border-t border-neutral-100">

    {{-- SCENT PROFILE --}}
    @if($product->scent_tone)

    <div class="grid grid-cols-12 gap-6 py-6 border-b border-neutral-100">

        <div class="col-span-3">
            <h3 class="text-sm font-serif font-bold lowercase text-black">
                scent profile
            </h3>
        </div>

        <div class="col-span-9">
            <p class="text-sm lowercase text-neutral-500 leading-relaxed font-light">
                {{ strtolower($product->scent_tone) }}
            </p>
        </div>

    </div>

    @endif

    {{-- TOP NOTES --}}
    @if($product->top_notes)

<div class="grid grid-cols-12 gap-6 py-6 border-b border-neutral-100">
        <div class="col-span-3">
            <h3 class="text-sm font-serif font-bold lowercase text-black">
                top notes
            </h3>
        </div>

        <div class="col-span-9">
            <p class="text-sm lowercase text-neutral-500 leading-relaxed font-light">
                {{ strtolower($product->top_notes) }}
            </p>
        </div>

    </div>

    @endif

    {{-- HEART NOTES --}}
    @if($product->heart_notes)

<div class="grid grid-cols-12 gap-6 py-6 border-b border-neutral-100">
        <div class="col-span-3">
            <h3 class="text-sm font-serif font-bold lowercase text-black">
                heart notes
            </h3>
        </div>

        <div class="col-span-9">
            <p class="text-sm lowercase text-neutral-500 leading-relaxed font-light">
                {{ strtolower($product->heart_notes) }}
            </p>
        </div>

    </div>

    @endif

    {{-- BASE NOTES --}}
    @if($product->base_notes)

<div class="grid grid-cols-12 gap-6 py-6 border-b border-neutral-100 color-black">
        <div class="col-span-3">
            <h3 class="text-sm font-serif font-bold lowercase text-black">
                base notes
            </h3>
        </div>

        <div class="col-span-9">
            <p class="text-sm lowercase text-neutral-500 leading-relaxed font-light">
                {{ strtolower($product->base_notes) }}
            </p>
        </div>

    </div>

    @endif

</div>

@endif
<script>
    function selectVolume(button) {

        // lấy tất cả button volume
        const buttons = document.querySelectorAll('.volume-btn');

        // remove selected khỏi tất cả
        buttons.forEach(btn => {
            btn.classList.remove(
                'selected',
                'border-yellow-500',
                'bg-yellow-500',
                'text-white'
            );

            // trả về style mặc định
            btn.classList.add(
                'border-gray-300',
                'text-gray-600'
            );
        });

        // add selected cho button đang click
        button.classList.add(
            'selected',
            'border-yellow-500',
            'bg-yellow-500',
            'text-white'
        );

        // optional: bỏ màu mặc định
        button.classList.remove(
            'border-gray-300',
            'text-gray-600'
        );

        // cập nhật hidden input
        document.getElementById('selected-volume').value =
            button.dataset.volume;

            document.getElementById('form-volume').value =
    button.dataset.volume;
    }
</script>