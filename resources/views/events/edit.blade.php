@extends('layouts.app')

@section('title', 'Chỉnh Sửa: ' . $event->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">

    {{-- Breadcrumb --}}
    <div class="mb-6 text-sm text-gray-500">
        <a href="{{ route('events.index') }}" class="hover:text-red-700">Sự Kiện</a>
        <span class="mx-2">/</span>
        <a href="{{ route('events.show', $event->id) }}" class="hover:text-red-700">{{ $event->title }}</a>
        <span class="mx-2">/</span>
        <span class="text-gray-800">Chỉnh Sửa</span>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <h1 class="text-xl font-bold text-gray-900">✏️ Chỉnh Sửa Sự Kiện</h1>
            <p class="text-sm text-gray-500 mt-1">Cập nhật thông tin sự kiện. Các trường có dấu <span class="text-red-500">*</span> là bắt buộc.</p>
        </div>

        <div class="p-6">
            <form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Validation Errors --}}
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Title --}}
                <div class="mb-5">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                        Tên Sự Kiện <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" id="title"
                           value="{{ old('title', $event->title) }}"
                           class="w-full border @error('title') border-red-400 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                           placeholder="Tên sự kiện...">
                    @error('title') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Category & Capacity --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Danh Mục <span class="text-red-500">*</span>
                        </label>
                        <select name="category_id" id="category_id"
                                class="w-full border @error('category_id') border-red-400 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $event->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="capacity" class="block text-sm font-medium text-gray-700 mb-1">
                            Sức Chứa <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="capacity" id="capacity"
                               value="{{ old('capacity', $event->capacity) }}"
                               min="10" max="5000"
                               class="w-full border @error('capacity') border-red-400 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                        @error('capacity') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Start & End Time --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">
                            Thời Gian Bắt Đầu <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" name="start_time" id="start_time"
                               value="{{ old('start_time', $event->start_time->format('Y-m-d\TH:i')) }}"
                               class="w-full border @error('start_time') border-red-400 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                        @error('start_time') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">
                            Thời Gian Kết Thúc <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" name="end_time" id="end_time"
                               value="{{ old('end_time', $event->end_time->format('Y-m-d\TH:i')) }}"
                               class="w-full border @error('end_time') border-red-400 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                        @error('end_time') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Location --}}
                <div class="mb-5">
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-1">
                        Địa Điểm <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="location" id="location"
                           value="{{ old('location', $event->location) }}"
                           class="w-full border @error('location') border-red-400 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                           placeholder="Địa điểm tổ chức...">
                    @error('location') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Banner --}}
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ảnh Banner</label>
                    @if($event->banner)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $event->banner) }}"
                                 alt="Banner hiện tại"
                                 class="h-32 rounded-lg object-cover border">
                            <p class="text-xs text-gray-500 mt-1">Banner hiện tại. Upload file mới để thay thế.</p>
                        </div>
                    @endif
                    <input type="file" name="banner" id="banner" accept="image/*"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    @error('banner') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Tags --}}
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                    <div class="flex flex-wrap gap-3 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                        @foreach($tags as $tag)
                            <label class="flex items-center gap-2 cursor-pointer text-sm">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                       {{ in_array($tag->id, $oldTags) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                <span class="text-gray-700">{{ $tag->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Description --}}
                <div class="mb-5">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        Mô Tả <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" id="description" rows="6"
                              class="w-full border @error('description') border-red-400 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                              placeholder="Nội dung mô tả chi tiết sự kiện...">{{ old('description', $event->description) }}</textarea>
                    @error('description') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Status --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Trạng Thái <span class="text-red-500">*</span>
                    </label>
                    <div class="flex flex-wrap gap-4">
                        @foreach(['draft' => 'Bản Nháp', 'published' => 'Xuất Bản', 'cancelled' => 'Đã Hủy'] as $value => $label)
                            <label class="flex items-center gap-2 cursor-pointer text-sm">
                                <input type="radio" name="status" value="{{ $value }}"
                                       {{ old('status', $event->status) === $value ? 'checked' : '' }}
                                       class="text-red-600 focus:ring-red-500">
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('status') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('events.show', $event->id) }}"
                       class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                        Hủy bỏ
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 text-sm font-semibold text-white bg-red-700 rounded-lg hover:bg-red-800 transition">
                        💾 Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
