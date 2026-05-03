@extends('layouts.app')

@section('content')

<div class="px-20 py-10">
    <h1 class="text-3xl font-serif mb-6">Xác nhận đơn hàng</h1>

    <form action="{{ route('checkout.confirm') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2">

                <h2 class="text-xl font-semibold mb-4">Sản phẩm</h2>

                @foreach($items as $item)
                    <div class="flex items-center gap-4 mb-4 border-b pb-4">
                        <img src="{{ $item->product->image ? (Illuminate\Support\Str::startsWith($item->product->image, ['http://','https://']) ? $item->product->image : asset('storage/products/' . urlencode($item->product->image))) : asset('storage/products/hero.jpeg') }}" class="w-20 h-20 object-cover rounded" />
                        <div class="flex-1">
                            <p class="font-medium">{{ $item->product->name }}</p>
                            <p class="text-sm text-gray-600">Số lượng: {{ $item->quantity }}</p>
                            <p class="text-sm text-gray-600">Giá: ${{ number_format($item->product->price, 2) }}</p>
                        </div>
                        <div>
                            <p class="font-semibold">${{ number_format($item->product->price * $item->quantity, 2) }}</p>
                        </div>
                    </div>

                    <input type="hidden" name="selected[]" value="{{ $item->id }}">
                @endforeach

            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm">
                <h2 class="text-xl font-semibold mb-4">Thông tin nhận hàng</h2>

                <label class="block text-sm">Tên người nhận</label>
                <input name="recipient_name" value="{{ old('recipient_name', $user->name) }}" class="w-full mb-3 border p-2 rounded" />

                <label class="block text-sm">Số điện thoại</label>
                <input name="recipient_phone" value="{{ old('recipient_phone', $user->phone) }}" class="w-full mb-3 border p-2 rounded" />

                <label class="block text-sm">Địa chỉ</label>
                <textarea name="recipient_address" class="w-full mb-3 border p-2 rounded">{{ old('recipient_address', $user->address) }}</textarea>

                <label class="block text-sm">Phương thức thanh toán</label>
                <select name="payment_method" class="w-full mb-3 border p-2 rounded">
                    <option value="C.O.D" selected>C.O.D (Thanh toán khi nhận hàng)</option>
                </select>

                <hr class="my-4">

                @if(session('voucher_error') || isset($voucher_error))
                    <div id="voucher-alert" class="mb-3 p-3 bg-red-100 text-red-700 rounded flex justify-between items-center">
                        <div class="flex-1">{{ session('voucher_error') ?? $voucher_error }}</div>
                        <button type="button" id="voucher-alert-close" class="ml-3 text-red-700 bg-transparent hover:text-red-900">✕</button>
                    </div>
                    <script>
                        (function(){
                            var btn = document.getElementById('voucher-alert-close');
                            if (btn) btn.addEventListener('click', function(){
                                var a = document.getElementById('voucher-alert');
                                if (a) a.style.display = 'none';
                            });
                        })();
                    </script>
                @endif

                <label class="block text-sm">Mã voucher</label>
                <input name="voucher_code" value="{{ old('voucher_code', $voucher_code ?? '') }}" placeholder="Nhập mã voucher" class="w-full mb-3 border p-2 rounded" />
<input type="hidden" name="price" value="{{ $total }}">

                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">Tổng</p>
                        <p class="text-lg font-semibold">${{ number_format($total, 2) }}</p>
                    </div>

                    <div class="flex gap-2">
                        <a href="/cart" class="px-4 py-2 border rounded">Quay lại giỏ hàng</a>
                        <button type="submit" class="px-4 py-2 bg-yellow-500 text-black rounded">Xác nhận và đặt hàng</button>
                    </div>
                </div>

            </div>

        </div>

    </form>

</div>

@endsection
