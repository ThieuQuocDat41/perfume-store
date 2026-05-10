@extends('layouts.app')

@section('content')

<div class="px-20 py-10">

    <h1 class="text-3xl font-serif mb-10">
        Order Details #{{ $order->id }}
    </h1>

    <div class="mb-4">

        <p>
            <strong>Order Date:</strong>
            {{ $order->created_at->format('Y-m-d H:i') }}
        </p>

        <p>
            <strong>Customer:</strong>
            {{ $order->user->name ?? '—' }}
            ({{ $order->user->email ?? '—' }})
        </p>

        @if($order->user->phone)
            <p>
                <strong>Phone Number:</strong>
                {{ $order->user->phone }}
            </p>
        @endif

        @if($order->user->address)
            <p>
                <strong>Delivery Address:</strong>
                {{ $order->user->address }}
            </p>
        @endif

        <p>
            <strong>Payment Method:</strong>
            {{ $order->payment_method ?? 'Cash on Delivery (C.O.D)' }}
        </p>

    </div>

    <p>
        <strong>Total Amount:</strong>
        ${{ $order->total_price }}
    </p>

    <p>
        <strong>Order Status:</strong>
        {{ ucfirst($order->status) }}
    </p>

    <hr class="my-6">

    <div class="space-y-4">

        @foreach($order->items as $item)

        <div class="flex gap-4 mb-4 items-center">
@php
    $imgs = is_array($item->product->images)
        ? $item->product->images
        : (json_decode($item->product->images, true) ?: []);

    $orderImg = $imgs[0] ?? asset('images/placeholder.png');

    if (!Illuminate\Support\Str::startsWith($orderImg, ['http://', 'https://'])) {
        $orderImg = asset('storage/' . ltrim($orderImg, '/'));
    }
@endphp

<img
    src="{{ $orderImg }}"
    class="w-24 h-24 object-cover bg-surface-container-low opacity-0 transition-opacity duration-700"
    onload="this.classList.add('opacity-100')"
    alt="{{ $item->product->name }}"
>
            <div class="flex-1">

                <p class="font-medium">
                    {{ $item->product->name }}
                </p>

                <p class="text-sm text-gray-600">
                    Quantity: {{ $item->quantity }}
                </p>

            </div>

            <div class="text-right">

                <p>
                    ${{ number_format($item->price, 2) }} / item
                </p>

                <p class="font-semibold">
                    Subtotal:
                    ${{ number_format($item->price * $item->quantity, 2) }}
                </p>

            </div>

        </div>

        @endforeach

    </div>

    <hr class="my-6">

    <div class="text-right">

        <p class="text-lg font-semibold">
            Grand Total:
            ${{ number_format($order->total_price, 2) }}
        </p>

    </div>

</div>

@endsection