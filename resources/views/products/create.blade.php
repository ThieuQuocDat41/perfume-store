@extends('layouts.app')

@section('content')

<div class="px-20 py-10">

    <h1 class="text-3xl font-serif mb-6">
        Thêm sản phẩm
    </h1>

    <form action="/products" method="POST" enctype="multipart/form-data">
        @csrf

        <input type="text" name="name" placeholder="Tên sản phẩm"
               class="block mb-3 border p-2 w-full">

        <input type="text" name="brand" placeholder="Thương hiệu"
               class="block mb-3 border p-2 w-full">

        <input type="number" name="price" placeholder="Giá"
               class="block mb-3 border p-2 w-full">

        <input type="file" name="image"
               class="block mb-3">

        <button class="bg-black text-white px-6 py-2">
            Thêm
        </button>

    </form>

</div>

@endsection