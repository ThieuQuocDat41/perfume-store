@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-semibold mb-4">Create Voucher</h1>

<form action="/admin/vouchers" method="POST" class="bg-white p-6 rounded shadow w-full max-w-md">
    @csrf

    <div class="mb-4">
        <label class="block mb-1">Code</label>
        <input name="code" value="{{ old('code') }}" class="w-full border p-2" />
        @error('code')<div class="text-red-600">{{ $message }}</div>@enderror
    </div>

    <div class="mb-4">
        <label class="block mb-1">Percent (1-99)</label>
        <input name="percent" type="number" min="1" max="99" value="{{ old('percent') }}" class="w-full border p-2" />
        @error('percent')<div class="text-red-600">{{ $message }}</div>@enderror
    </div>

    <div>
        <button class="px-4 py-2 bg-green-600 text-white rounded">Create</button>
        <a href="/admin/vouchers" class="ml-2 px-4 py-2 bg-gray-300 rounded">Cancel</a>
    </div>
</form>

@endsection
