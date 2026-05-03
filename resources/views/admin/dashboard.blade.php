@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-semibold mb-4">Admin Dashboard</h1>

<div class="grid grid-cols-3 gap-6">
    <a href="/admin/users" class="p-6 bg-white rounded shadow">Users<br><span class="text-sm text-gray-500">Manage registered users</span></a>
    <a href="/admin/products" class="p-6 bg-white rounded shadow">Products<br><span class="text-sm text-gray-500">Manage products</span></a>
    <a href="/admin/vouchers" class="p-6 bg-white rounded shadow">Vouchers<br><span class="text-sm text-gray-500">Create / Delete / View voucher codes</span></a>
</div>

@endsection
