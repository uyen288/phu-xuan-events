@extends('layouts.app')

@section('title', 'Tạo Tài Khoản Mới')

@section('content')
<div class="max-w-xl mx-auto px-4 py-8">

    <div class="mb-6 text-sm text-gray-500">
        <a href="{{ route('admin.users.index') }}" class="hover:text-red-700">Quản Lý Tài Khoản</a>
        <span class="mx-2">/</span>
        <span class="text-gray-800">Tạo Mới</span>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <h1 class="text-xl font-bold text-gray-900">+ Tạo Tài Khoản Mới</h1>
        </div>

        <div class="p-6">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                @if($errors->any())
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <ul class="text-sm text-red-700 space-y-1 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Họ Tên *</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mật Khẩu *</label>
                        <input type="password" name="password"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Xác Nhận Mật Khẩu *</label>
                        <input type="password" name="password_confirmation"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Vai Trò *</label>
                        <select name="role"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="student" {{ old('role', 'student') === 'student' ? 'selected' : '' }}>Student</option>
                            <option value="organizer" {{ old('role') === 'organizer' ? 'selected' : '' }}>Organizer</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.users.index') }}"
                       class="px-5 py-2.5 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                        Hủy
                    </a>
                    <button type="submit"
                            class="px-5 py-2.5 text-sm font-semibold text-white bg-red-700 rounded-lg hover:bg-red-800 transition">
                        Tạo tài khoản
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
