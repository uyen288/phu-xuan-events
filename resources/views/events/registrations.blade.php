@extends('layouts.app')

@section('title', 'Danh Sách Đăng Ký: ' . $event->title)

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">

    {{-- Breadcrumb --}}
    <div class="mb-6 text-sm text-gray-500">
        <a href="{{ route('events.index') }}" class="hover:text-red-700">Sự Kiện</a>
        <span class="mx-2">/</span>
        <a href="{{ route('events.show', $event->id) }}" class="hover:text-red-700">{{ $event->title }}</a>
        <span class="mx-2">/</span>
        <span class="text-gray-800">Danh Sách Đăng Ký</span>
    </div>

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">👥 Danh Sách Đăng Ký</h1>
            <p class="text-gray-500 text-sm mt-1">
                Sự kiện: <span class="font-semibold text-gray-700">{{ $event->title }}</span>
                &mdash; {{ $registrations->total() }} người đăng ký
            </p>
        </div>
        <a href="{{ route('events.show', $event->id) }}"
           class="text-sm text-gray-600 border border-gray-200 px-4 py-2 rounded-lg hover:bg-gray-50 transition">
            ← Quay lại
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-800 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-yellow-700">
                {{ $registrations->where('status', 'pending')->count() }}
            </div>
            <div class="text-xs text-yellow-600 mt-1">Chờ duyệt</div>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-green-700">
                {{ $registrations->where('status', 'confirmed')->count() }}
            </div>
            <div class="text-xs text-green-600 mt-1">Đã duyệt</div>
        </div>
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-gray-700">
                {{ $event->capacity }}
            </div>
            <div class="text-xs text-gray-500 mt-1">Sức chứa</div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">STT</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Sinh Viên</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Ngày Đăng Ký</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Ghi Chú</th>
                    <th class="text-center px-4 py-3 font-semibold text-gray-600">Trạng Thái</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($registrations as $i => $reg)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-gray-500">
                            {{ ($registrations->currentPage() - 1) * $registrations->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900">{{ $reg->user->name ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">{{ $reg->user->email ?? '' }}</div>
                        </td>
                        <td class="px-4 py-3 text-gray-500">
                            {{ $reg->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-4 py-3 text-gray-600 max-w-xs truncate">
                            {{ $reg->note ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($reg->status === 'confirmed')
                                <span class="inline-block bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    Đã duyệt
                                </span>
                            @elseif($reg->status === 'pending')
                                <span class="inline-block bg-yellow-100 text-yellow-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    Chờ duyệt
                                </span>
                            @else
                                <span class="inline-block bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    Đã hủy
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-gray-400">
                            Chưa có ai đăng ký sự kiện này.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $registrations->links() }}
    </div>

</div>
@endsection
