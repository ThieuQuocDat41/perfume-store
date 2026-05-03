@extends('layouts.app')

@section('content')

<div class="bg-[#fdf8f8] min-h-screen px-20 py-10">

    <h1 class="text-3xl font-serif mb-10">
        Chỉnh sửa thông tin
    </h1>

<form method="POST" action="{{ route('profile.update') }}">
    @csrf
    @method('PATCH')
        <div class="grid grid-cols-2 gap-6">

            <div>
                <label>Họ tên</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                       class="w-full border p-2">
            </div>

            <div>
                <label>Email</label>
                <input type="email" name="email" value="{{ $user->email }}"
                       class="w-full border p-2">
            </div>

            <div>
                <label>Số điện thoại</label>
                <input type="text" name="phone" value="{{ $user->phone }}"
                       class="w-full border p-2">
            </div>

            <div>
                <label>Giới tính</label>
                <select name="gender" class="w-full border p-2">
                    <option value="">-- Chọn --</option>
                    <option value="Nam" {{ $user->gender == 'Nam' ? 'selected' : '' }}>Nam</option>
                    <option value="Nữ" {{ $user->gender == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                </select>
            </div>

            <div>
                <label>Ngày sinh</label>
                <input type="date" name="birthday" value="{{ $user->birthday }}"
                       class="w-full border p-2">
            </div>

            <div>
                <label>Địa chỉ</label>
                <input type="text" name="address" value="{{ $user->address }}"
                       class="w-full border p-2">
            </div>

        </div>

        <button class="mt-6 bg-yellow-500 px-6 py-2">
            Lưu thay đổi
        </button>

    </form>

</div>

@endsection