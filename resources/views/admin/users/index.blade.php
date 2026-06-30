@extends('layouts.app')

@section('title', 'Quản Lý Tài Khoản')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">👤 Quản Lý Tài Khoản</h1>
            <p class="text-gray-500 text-sm mt-1">Tổng: {{ $users->total() }} tài khoản</p>
        </div>
        <a href="{{ route('admin.users.create') }}"
           class="bg-red-700 text-white font-semibold px-4 py-2.5 rounded-lg hover:bg-red-800 transition text-sm">
            + Tạo tài khoản mới
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-800 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-800 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- Search & Filter --}}
    <form action="{{ route('admin.users.index') }}" method="GET" class="mb-6 flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Tìm tên hoặc email..."
               class="flex-1 min-w-[200px] border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
        <select name="role" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            <option value="">-- Tất cả vai trò --</option>
            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="organizer" {{ request('role') === 'organizer' ? 'selected' : '' }}>Organizer</option>
            <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Student</option>
        </select>
        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700 transition">
            Lọc
        </button>
        @if(request()->hasAny(['search', 'role']))
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                Xóa lọc
            </a>
        @endif
    </form>

    {{-- Table --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">ID</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Họ Tên</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Email</th>
                    <th class="text-center px-4 py-3 font-semibold text-gray-600">Vai Trò</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Ngày Tạo</th>
                    <th class="text-center px-4 py-3 font-semibold text-gray-600">Hành Động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-gray-500 font-mono text-xs">{{ $user->id }}</td>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($user->isAdmin())
                                <span class="bg-red-100 text-red-700 text-xs font-bold px-2.5 py-1 rounded-full uppercase">Admin</span>
                            @elseif($user->isOrganizer())
                                <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-full uppercase">Organizer</span>
                            @else
                                <span class="bg-gray-100 text-gray-600 text-xs font-bold px-2.5 py-1 rounded-full uppercase">Student</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs">
                            {{ $user->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                   class="text-xs bg-gray-100 text-gray-700 px-3 py-1.5 rounded-lg hover:bg-gray-200 transition font-medium">
                                    Sửa
                                </a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                          onsubmit="return confirm('Xóa tài khoản {{ $user->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-xs bg-red-50 text-red-600 px-3 py-1.5 rounded-lg hover:bg-red-100 transition font-medium">
                                            Xóa
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-gray-400">
                            Không tìm thấy tài khoản nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $users->withQueryString()->links() }}</div>
</div>
@endsection
