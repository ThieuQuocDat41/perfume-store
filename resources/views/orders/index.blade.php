@extends('layouts.app')

@section('content')

<div class="bg-[#fdf8f8] min-h-screen px-6 md:px-20 py-16">

    <!-- HEADER -->
    <div class="mb-16">
        <h1 class="text-4xl md:text-5xl font-serif text-[#1c1b1b]">
            Đơn hàng của bạn
        </h1>


    </div>

    <!-- ORDER LIST -->
    <div class="space-y-8">

        @forelse($orders as $order)

        <div class="bg-white rounded-xl border border-gray-100 p-6 
                    flex flex-col md:flex-row md:items-center md:justify-between
                    gap-6 transition-all duration-300 
                    hover:-translate-y-1 hover:shadow-md">

            <!-- LEFT: IMAGE -->
            <div class="flex gap-6 items-center">

                <img 
                    src="{{ $order->items->first()?->product->image 
                        ? (Illuminate\Support\Str::startsWith($order->items->first()->product->image, ['http://','https://']) ? $order->items->first()->product->image : asset('storage/products/' . urlencode($order->items->first()->product->image))) 
                        : asset('storage/products/hero.jpeg') }}"
                    class="w-32 h-32 object-cover rounded-lg bg-surface-container-low opacity-0 transition-opacity duration-700"
                    onload="this.classList.add('opacity-100')"
                >

                <!-- INFO GRID -->
                <div class="grid grid-cols-2 md:grid-cols-5 gap-6 text-sm">

                    <!-- ORDER ID -->
                    <div>
                        <p class="uppercase tracking-widest text-gray-400 text-xs">
                            Order ID
                        </p>
                        <p class="font-semibold text-[#1c1b1b]">
                            #TTP-{{ $order->id }}
                        </p>
                    </div>

                    <!-- DATE -->
                    <div>
                        <p class="uppercase tracking-widest text-gray-400 text-xs">
                            Date
                        </p>
                        <p class="text-[#1c1b1b]">
                            {{ $order->created_at->format('d M Y') }}
                        </p>
                    </div>

                    <!-- TOTAL -->
                    <div>
                        <p class="uppercase tracking-widest text-gray-400 text-xs">
                            Total
                        </p>
                        <p class="font-serif text-lg">
                            ${{ number_format($order->total_price, 2) }}
                        </p>
                    </div>

                    <!-- STATUS -->
                    <div>
                        <p class="uppercase tracking-widest text-gray-400 text-xs">
                            Status
                        </p>

                        @if($order->status == 'processing')
                            <span class="inline-flex px-3 py-1 rounded-full text-xs bg-gray-100 text-gray-600">
                                Processing
                            </span>
                        @elseif($order->status == 'shipped')
                            <span class="inline-flex px-3 py-1 rounded-full text-xs bg-[#d4af37]/10 text-[#d4af37]">
                                Shipped
                            </span>
                        @elseif($order->status == 'delivered')
                            <span class="inline-flex px-3 py-1 rounded-full text-xs bg-gray-200 text-gray-700">
                                Delivered
                            </span>
                        @else
                            <span class="inline-flex px-3 py-1 rounded-full text-xs bg-gray-100">
                                {{ $order->status }}
                            </span>
                        @endif

                    </div>

                    <!-- PAYMENT -->
                    <div>
                        <p class="uppercase tracking-widest text-gray-400 text-xs">
                            Payment
                        </p>
                        <p class="text-[#1c1b1b]">
                            {{ $order->payment_method ?? 'C.O.D' }}
                        </p>
                    </div>

                </div>

            </div>

            <!-- ACTIONS -->
            <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">

                <!-- DETAILS -->
                <a href="{{ route('orders.show', $order->id) }}"
                   class="px-5 py-2 border text-sm text-center hover:bg-gray-100 transition w-full md:w-auto">
                    Details
                </a>

                <!-- PRIMARY ACTION -->
                @if($order->status == 'processing')
                    <button class="px-5 py-2 bg-[#d4af37] text-black text-sm w-full md:w-auto">
                        Track
                    </button>
                @else
                    <button class="px-5 py-2 bg-black text-white text-sm w-full md:w-auto">
                        Reorder
                    </button>
                @endif

            </div>

        </div>

        @empty

        <!-- EMPTY STATE -->
        <div class="text-center py-20 text-gray-500">
            Bạn chưa có đơn hàng nào.
        </div>

        @endforelse

    </div>

    <!-- SPACING -->
    <div class="h-[120px]"></div>

<!-- CTA SHOPPING -->
<div class="bg-neutral-900 text-center py-20 px-6 rounded-xl">

    <h3 class="text-3xl font-serif text-white">
        Discover Your Next Signature Scent
    </h3>

    <p class="text-gray-400 mt-4 max-w-xl mx-auto">
    Đừng để giỏ hàng trống như cuộc tình của bạn -.-
</p>

    <a href="/products"
       class="inline-block mt-8 px-10 py-3 bg-[#d4af37] text-black tracking-widest text-sm 
              hover:scale-105 transition-all duration-300">
        SHOP NOW
    </a>

</div>

</div>

@endsection