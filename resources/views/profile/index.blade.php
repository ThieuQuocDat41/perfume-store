@extends('layouts.app')

@section('content')

<div class="bg-[#fdf8f8] min-h-screen px-20 py-10">

    <!-- PROFILE HEADER -->
    <div class="flex items-center gap-10">

        <!-- AVATAR -->
@if($user->avatar)

    <!-- Avatar thật -->
    <img src="{{ asset('storage/avatars/'.$user->avatar) }}"
         class="w-40 h-40 rounded-full object-cover border-4 border-white shadow-lg">

@else

    <!-- Avatar mặc định (SVG) -->
    <div class="w-40 h-40 rounded-full bg-neutral-300 flex items-center justify-center overflow-hidden">

        <svg viewBox="0 0 100 100" class="w-24 h-24">

            <!-- Head -->
            <circle cx="50" cy="35" r="18" fill="white" />

            <!-- Shoulders -->
            <path d="M20 85
                     C20 65, 80 65, 80 85
                     Z"
                  fill="white" />

        </svg>

    </div>

@endif

        <!-- INFO -->
        <div>
            <h1 class="text-4xl font-serif">
                {{ $user->name }}
            </h1>

            <p class="text-gray-500 uppercase tracking-widest text-sm mt-2">
                Member since {{ $user->created_at->format('F Y') }}
            </p>

            <span class="bg-yellow-100 text-yellow-600 px-3 py-1 rounded-full text-sm mt-3 inline-block">
                Gold Tier Member
            </span>
        </div>

    </div>

    <!-- STATS -->
  <!-- STATISTICS -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-10">

    <!-- TOTAL ORDERS (CLICKABLE) -->
    <a href="{{ route('orders.index') }}"
       class="bg-white rounded-lg p-6 shadow-[0_20px_40px_rgba(0,0,0,0.04)]
              transition-all duration-300 cursor-pointer
              hover:-translate-y-1 hover:shadow-md group">

        <p class="text-xs uppercase tracking-widest text-gray-400 group-hover:text-[#d4af37]">
            Tổng đơn hàng
        </p>

        <p class="text-3xl font-serif mt-2 group-hover:text-[#d4af37]">
            {{ $totalOrders }}
        </p>

    </a>


    <!-- TOTAL SPENT (STATIC) -->
    <div class="bg-white rounded-lg p-6 shadow-[0_20px_40px_rgba(0,0,0,0.04)]
                cursor-default">

        <p class="text-xs uppercase tracking-widest text-gray-400">
            Tổng chi tiêu
        </p>

        <p class="text-3xl font-serif mt-2">
            ${{ number_format($totalSpent, 2) }}
        </p>

    </div>


    <!-- LOYALTY TIER (STATIC) -->
    <div class="bg-white rounded-lg p-6 shadow-[0_20px_40px_rgba(0,0,0,0.04)]
                cursor-default">

        <p class="text-xs uppercase tracking-widest text-gray-400">
            Hạng thành viên
        </p>

        <p class="text-3xl font-serif mt-2 text-[#d4af37]">
            Platinum
        </p>

    </div>

</div>

    <!-- PERSONAL DETAILS -->
    <div class="bg-white mt-10 p-8 rounded-xl shadow">

        <div class="flex justify-between mb-6">
            <h2 class="text-2xl font-serif">Thông tin cá nhân</h2>
        <a href="{{ route('profile.edit') }}" class="border px-4 py-2 text-sm">
    CHỈNH SỬA
</a>
        </div>

        <div class="grid grid-cols-2 gap-6">

            <div>
                <p class="text-sm text-gray-400 uppercase">Full Name</p>
                <p class="border-b py-2">{{ $user->name }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-400 uppercase">Gender</p>
                <p class="border-b py-2">{{ $user->gender ?? '-' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-400 uppercase">Email</p>
                <p class="border-b py-2">{{ $user->email }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-400 uppercase">Phone</p>
                <p class="border-b py-2">{{ $user->phone ?? '-' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-400 uppercase">Birthday</p>
                <p class="border-b py-2">
                    {{ $user->birthday ? \Carbon\Carbon::parse($user->birthday)->format('F d, Y') : '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-400 uppercase">Address</p>
                <p class="border-b py-2">{{ $user->address ?? '-' }}</p>
            </div>

        </div>

    </div>

</div>

@endsection