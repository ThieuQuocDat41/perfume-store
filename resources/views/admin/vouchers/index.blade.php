@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-semibold mb-4">Vouchers</h1>

@if(session('status'))
    <div class="mb-4 p-3 bg-green-100 text-green-800">{{ session('status') }}</div>
@endif

<a href="/admin/vouchers/create" class="mb-4 inline-block px-4 py-2 bg-blue-600 text-white rounded">Create voucher</a>

<table class="w-full bg-white rounded shadow">
    <thead class="bg-gray-100">
        <tr>
            <th class="p-3 text-left">ID</th>
            <th class="p-3 text-left">Code</th>
            <th class="p-3 text-left">Percent</th>
            <th class="p-3 text-left">Actions</th>
        </tr>
    </thead>
    <tbody>
    @foreach($vouchers as $v)
        <tr class="border-t">
            <td class="p-3">{{ $v->id }}</td>
            <td class="p-3">{{ $v->code }}</td>
            <td class="p-3">{{ $v->percent }}%</td>
            <td class="p-3">
                <form action="/admin/vouchers/{{ $v->id }}/delete" method="POST" class="inline" onsubmit="return confirm('Delete voucher {{ $v->code }}?');">
                    @csrf
                    <button class="px-3 py-1 bg-red-500 text-white rounded">Delete</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="mt-4">
    {{ $vouchers->links() }}
</div>

@endsection
