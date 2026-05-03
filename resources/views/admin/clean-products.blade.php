@extends('layouts.app')

@section('content')
<div class="px-20 py-10">
    <h1 class="text-2xl font-semibold">Products with missing images</h1>

    @if($missing->isEmpty())
        <p class="mt-4 text-gray-500">No missing images found.</p>
    @else
        <ul class="mt-4 space-y-2">
            @foreach($missing as $p)
                <li class="flex items-center justify-between bg-white p-3 rounded shadow">
                    <div>
                        <strong>#{{ $p->id }}</strong> — {{ $p->name }} <span class="text-sm text-gray-500">(image: {{ $p->image }})</span>
                    </div>
                    <div class="flex gap-2">
                        <form action="{{ url('/admin/products/'.$p->id.'/delete') }}" method="POST" onsubmit="return confirm('Delete product #'+{{ $p->id }}+'?');">
                            @csrf
                            <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded">Delete</button>
                        </form>
                        @if($loop->first)
                            <a href="{{ url('/admin/products/clean-missing?confirm=1') }}" class="px-3 py-1 bg-red-500 text-white rounded">Delete all missing</a>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
