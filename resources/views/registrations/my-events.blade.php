@extends('layouts.app')

@section('title', 'Sự Kiện Của Tôi')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">📋 Sự Kiện Của Tôi</h1>
        <p class="text-gray-500 text-sm mt-1">Danh sách các sự kiện bạn đã đăng ký tham gia.</p>
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

    @if($registrations->isEmpty())
        <div class="bg-white border border-gray-200 rounded-xl p-12 text-center">
            <div class="text-5xl mb-4">📭</div>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Bạn chưa đăng ký sự kiện nào</h3>
            <p class="text-gray-500 text-sm mb-6">Khám phá các sự kiện đang diễn ra và đăng ký tham gia ngay!</p>
            <a href="{{ route('events.index') }}"
               class="inline-block bg-red-700 text-white font-semibold px-6 py-2.5 rounded-lg hover:bg-red-800 transition">
                Khám phá sự kiện
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($registrations as $registration)
                @php $event = $registration->event; @endphp
                <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm flex flex-col md:flex-row justify-between gap-4">

                    {{-- Event info --}}
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">
                                {{ $event->category->name ?? 'Sự kiện' }}
                            </span>
                            {{-- Status badge --}}
                            @if($registration->status === 'confirmed')
                                <span class="text-xs bg-green-100 text-green-700 font-semibold px-2 py-0.5 rounded-full">✅ Đã duyệt</span>
                            @elseif($registration->status === 'pending')
                                <span class="text-xs bg-yellow-100 text-yellow-700 font-semibold px-2 py-0.5 rounded-full">⏳ Chờ duyệt</span>
                            @else
                                <span class="text-xs bg-red-100 text-red-700 font-semibold px-2 py-0.5 rounded-full">❌ Đã hủy</span>
                            @endif
                        </div>

                        <h3 class="font-bold text-gray-900 text-lg mb-1">
                            <a href="{{ route('events.show', $event->id) }}" class="hover:text-red-700">
                                {{ $event->title }}
                            </a>
                        </h3>

                        <div class="text-sm text-gray-500 space-y-1">
                            <div>📍 {{ $event->location }}</div>
                            <div>🕐 {{ $event->start_time->format('H:i d/m/Y') }}</div>
                            <div>📅 Đăng ký ngày: {{ $registration->created_at->format('d/m/Y H:i') }}</div>
                            @if($registration->note)
                                <div>📝 Ghi chú: {{ $registration->note }}</div>
                            @endif
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col gap-2 min-w-[140px]">
                        <a href="{{ route('events.show', $event->id) }}"
                           class="text-center text-sm text-red-700 border border-red-200 font-medium px-4 py-2 rounded-lg hover:bg-red-50 transition">
                            Xem chi tiết
                        </a>

                        @if($registration->status === 'pending')
                            <form action="{{ route('registrations.cancel', $event->id) }}" method="POST"
                                  onsubmit="return confirm('Bạn có chắc muốn hủy đăng ký không?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-full text-sm text-gray-600 border border-gray-200 font-medium px-4 py-2 rounded-lg hover:bg-gray-50 transition">
                                    Hủy đăng ký
                                </button>
                            </form>
                        @endif
                    </div>

                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $registrations->links() }}
        </div>
    @endif

</div>
@endsection
