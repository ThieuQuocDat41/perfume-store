@extends('layouts.app')

@section('content')

<div class="px-20 py-10">

    <h1 class="text-3xl font-serif mb-10">
        Chi tiết đơn hàng #{{ $order->id }}
    </h1>

    <div class="mb-4">
        <p><strong>Ngày tạo:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
        <p><strong>Khách hàng:</strong> {{ $order->user->name ?? '—' }} ({{ $order->user->email ?? '—' }})</p>
        @if($order->user->phone)
            <p><strong>Phone:</strong> {{ $order->user->phone }}</p>
        @endif
        @if($order->user->address)
            <p><strong>Địa chỉ:</strong> {{ $order->user->address }}</p>
        @endif
        <p><strong>Phương thức thanh toán:</strong> {{ $order->payment_method ?? 'C.O.D' }}</p>
    </div>

    <p><strong>Tổng tiền:</strong> ${{ $order->total_price }}</p>
    <p><strong>Trạng thái:</strong> {{ $order->status }}</p>

    <hr class="my-6">

    <div class="space-y-4">
    @foreach($order->items as $item)

    <div class="flex gap-4 mb-4 items-center">

           <img src="{{ $item->product->image ? (Illuminate\Support\Str::startsWith($item->product->image, ['http://','https://']) ? $item->product->image : asset('storage/products/' . urlencode($item->product->image))) : asset('storage/products/hero.jpeg') }}"
               class="w-24 h-24 object-cover bg-surface-container-low opacity-0 transition-opacity duration-700"
               onload="this.classList.add('opacity-100')">

        <div class="flex-1">
            <p class="font-medium">{{ $item->product->name }}</p>
            <p class="text-sm text-gray-600">Số lượng: {{ $item->quantity }}</p>
        </div>

        <div class="text-right">
            <p>${{ number_format($item->price, 2) }} / cái</p>
            <p class="font-semibold">Tổng: ${{ number_format($item->price * $item->quantity, 2) }}</p>
        </div>

    </div>

    @endforeach

    </div>

    <hr class="my-6">

    <div class="text-right">
        <p class="text-lg font-semibold">Tổng thanh toán: ${{ number_format($order->total_price, 2) }}</p>
    </div>

</div>

@endsection