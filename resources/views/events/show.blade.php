@extends('layouts.app')

@section('title', $event->title)

@section('content')
    <div class="max-w-5xl mx-auto px-4 py-8">

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-800 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-800 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        {{-- Breadcrumb --}}
        <div class="mb-6 text-sm text-gray-500">
            <a href="{{ route('events.index') }}" class="hover:text-red-700">Sự Kiện</a>
            <span class="mx-2">/</span>
            <span class="text-gray-800">{{ $event->title }}</span>
        </div>

        {{-- Banner --}}
        @if($event->banner)
            @if(str_starts_with($event->banner, 'image/'))
                <img src="{{ asset($event->banner) }}" alt="{{ $event->title }}"
                    class="w-full h-64 md:h-96 object-cover rounded-xl mb-8 shadow"
                    onerror="this.onerror=null; this.src='{{ asset('image/slider1.jpg') }}';">
            @else
                <img src="{{ asset('storage/' . $event->banner) }}" alt="{{ $event->title }}"
                    class="w-full h-64 md:h-96 object-cover rounded-xl mb-8 shadow"
                    onerror="this.onerror=null; this.src='{{ asset('image/slider1.jpg') }}';">
            @endif
        @else
            <img src="{{ asset('image/slider1.jpg') }}" alt="PXU Event Mặc định"
                class="w-full h-64 md:h-96 object-cover rounded-xl mb-8 shadow">
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Main Content --}}
            <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                {{-- Category Badge --}}
                <span
                    class="inline-block bg-gradient-to-r from-red-100 to-red-50 text-red-700 text-xs font-bold px-4 py-1.5 rounded-full mb-4 border border-red-200 shadow-sm">
                    {{ $event->category->name ?? 'Chưa phân loại' }}
                </span>

                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $event->title }}</h1>

                {{-- Meta --}}
                <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-6">
                    <div class="flex items-center gap-1">
                        <span>👤</span>
                        <span>{{ $event->user->name ?? 'Ban Tổ Chức' }}</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span>📍</span>
                        <span>{{ $event->location }}</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span>🕐</span>
                        <span>{{ $event->start_time->format('H:i d/m/Y') }}</span>
                        <span>→</span>
                        <span>{{ $event->end_time->format('H:i d/m/Y') }}</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span>👥</span>
                        <span>{{ $event->registrations->count() }} / {{ $event->capacity }} chỗ</span>
                    </div>
                </div>

                {{-- Tags --}}
                @if($event->tags->isNotEmpty())
                    <div class="flex flex-wrap gap-2 mb-6">
                        @foreach($event->tags as $tag)
                            <a href="{{ route('events.index', ['tag' => $tag->id]) }}"
                                class="bg-gray-100 hover:bg-red-100 text-gray-700 hover:text-red-700 transition duration-200 text-xs px-3 py-1.5 rounded-full border border-gray-200 hover:border-red-200 shadow-sm cursor-pointer">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                @endif

                {{-- Description --}}
                <div class="prose max-w-none text-gray-700 leading-relaxed whitespace-pre-line">
                    {{ $event->description }}
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-4">

                {{-- Status Card --}}
                <div
                    class="bg-white border border-gray-100 rounded-2xl p-6 shadow-md hover:shadow-lg transition duration-300">
                    <h3 class="font-semibold text-gray-800 mb-3">Thông tin sự kiện</h3>

                    <div class="text-sm text-gray-600 space-y-2">
                        <div>
                            <span class="font-medium">Trạng thái: </span>
                            @if($event->status === 'published')
                                <span class="text-green-600 font-semibold">Đang diễn ra</span>
                            @elseif($event->status === 'draft')
                                <span class="text-gray-500">Bản nháp</span>
                            @elseif($event->status === 'cancelled')
                                <span class="text-red-600">Đã hủy</span>
                            @else
                                <span class="text-blue-600">Đã kết thúc</span>
                            @endif
                        </div>
                        <div>
                            <span class="font-medium">Bắt đầu: </span>
                            {{ $event->start_time->format('d/m/Y H:i') }}
                        </div>
                        <div>
                            <span class="font-medium">Kết thúc: </span>
                            {{ $event->end_time->format('d/m/Y H:i') }}
                        </div>
                        <div>
                            <span class="font-medium">Sức chứa: </span>
                            {{ $event->registrations->count() }} / {{ $event->capacity }}
                        </div>
                    </div>
                </div>

                {{-- Action Card --}}
                <div
                    class="bg-white border border-gray-100 rounded-2xl p-6 shadow-md hover:shadow-lg transition duration-300">
                    @auth
                        {{-- ĐỔI DÒNG NÀY: Từ isStudent() sang check chuỗi role giống trang index --}}
                        @if(auth()->user()->role === 'student')
                            @if($isRegistered)
                                {{-- Cancel Registration --}}
                                <p class="text-green-600 font-semibold text-sm mb-3">✅ Bạn đã đăng ký sự kiện này.</p>
                                <form action="{{ route('registrations.cancel', $event->id) }}" method="POST"
                                    onsubmit="return confirm('Bạn có chắc muốn hủy đăng ký không?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full bg-red-100 text-red-700 border border-red-300 font-semibold py-2 px-4 rounded-lg hover:bg-red-200 transition text-sm">
                                        Hủy đăng ký
                                    </button>
                                </form>
                            @elseif($event->registrations->count() >= $event->capacity)
                                <p class="text-center text-gray-500 text-sm font-semibold">Sự kiện đã đầy chỗ.</p>
                            @elseif($event->status !== 'published')
                                <p class="text-center text-gray-500 text-sm">Sự kiện chưa mở đăng ký.</p>
                            @else
                                {{-- Register --}}
                                <form action="{{ route('registrations.store', $event->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="note" class="block text-sm font-medium text-gray-700 mb-1">
                                            Ghi chú (tùy chọn)
                                        </label>
                                        <textarea name="note" id="note" rows="3"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                                            placeholder="Ghi chú cho ban tổ chức..."></textarea>
                                    </div>
                                    <button type="submit"
                                        class="w-full bg-green-700 text-white font-semibold py-2 px-4 rounded-lg hover:bg-red-800 transition">
                                        Đăng ký tham gia
                                    </button>
                                </form>
                            @endif
                        @endif

                        {{-- Organizer/Admin controls --}}
                        @can('update', $event)
                            <div class="border-t border-gray-100 pt-3 mt-3 space-y-2">
                                <a href="{{ route('events.edit', $event->id) }}"
                                    class="block w-full text-center bg-gray-800 text-white font-semibold py-2 px-4 rounded-lg hover:bg-gray-700 transition text-sm">
                                    ✏️ Chỉnh sửa sự kiện
                                </a>
                                <a href="{{ route('events.registrations', $event->id) }}"
                                    class="block w-full text-center bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700 transition text-sm">
                                    👥 Xem danh sách đăng ký
                                </a>
                            </div>
                        @endcan

                        @can('delete', $event)
                            <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="mt-2"
                                onsubmit="return confirm('Xóa sự kiện này? Hành động không thể hoàn tác.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full text-center bg-red-50 text-red-600 border border-red-200 font-semibold py-2 px-4 rounded-lg hover:bg-red-100 transition text-sm">
                                    🗑 Xóa sự kiện
                                </button>
                            </form>
                        @endcan
                    @else
                        <p class="text-sm text-gray-600 mb-3">Đăng nhập để đăng ký tham gia sự kiện.</p>
                        <a href="{{ route('login') }}"
                            class="block w-full text-center bg-red-700 text-white font-semibold py-2 px-4 rounded-lg hover:bg-red-800 transition">
                            Đăng nhập
                        </a>
                    @endauth
                </div>
            </div>

        </div>
@endsection