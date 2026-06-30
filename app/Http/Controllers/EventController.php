<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use App\Models\Category;
use App\Models\Event;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller
{
    // ĐÃ XÓA hàm __construct() lỗi middleware ở đây để tương thích hoàn toàn với cấu trúc routing mới.

    // M2.1: Hiển thị danh sách sự kiện công khai kèm bộ lọc nâng cao
    public function index(Request $request)
    {
        $query = Event::with(['category', 'tags', 'user'])
            ->withCount('registrations') // Tính toán số slot đã đặt (registrations_count)
            ->published() // Cần viết local scopePublished trong Model Event
            ->upcoming(); // Cần viết local scopeUpcoming trong Model Event

        // Lọc theo danh mục
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Lọc theo từ khóa tìm kiếm (Tên sự kiện hoặc địa điểm)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('location', 'LIKE', "%{$search}%");
            });
        }

        $events = $query->orderBy('start_time', 'asc')->paginate(12);
        $categories = Category::all();

        return view('events.index', compact('events', 'categories'));
    }

    // M2.2: Chi tiết sự kiện
    public function show(Event $event)
    {
        $this->authorize('view', $event);

        // Nạp thêm thông tin liên quan để tránh lỗi N+1 Query
        $event->load(['category', 'tags', 'user', 'registrations']);

        // Kiểm tra xem sinh viên hiện tại đã bấm đăng ký tham gia sự kiện này chưa
        $isRegistered = false;
        if (Auth::check()) {
            $isRegistered = $event->registrations()->where('user_id', Auth::id())->exists();
        }

        return view('events.show', compact('event', 'isRegistered'));
    }

    // Form giao diện Tạo sự kiện
    public function create()
    {
        Gate::authorize('create', Event::class);

        $categories = Category::all();
        $tags = Tag::all();

        return view('events.create', compact('categories', 'tags'));
    }

    // Lưu trữ sự kiện mới vào Database
    public function store(EventRequest $request)
    {
        $this->authorize('create', Event::class);

        $data = $request->validated();
        $data['user_id'] = Auth::id(); // Gán ID của Organizer đang đăng nhập làm người tạo

        // Xử lý upload ảnh banner (M2.4)
        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->store('events/banners', 'public');
            $data['banner'] = $path;
        }

        $event = Event::create($data);

        // Đồng bộ dữ liệu bảng trung gian (Pivot Table: event_tag)
        if ($request->has('tags')) {
            $event->tags()->sync($request->tags);
        }

        return redirect()->route('events.show', $event->id)
            ->with('success', 'Chúc mừng! Sự kiện của bạn đã được khởi tạo thành công.');
    }

    // Form giao diện Sửa sự kiện
    public function edit(Event $event)
    {
        $this->authorize('update', $event);

        $categories = Category::all();
        $tags = Tag::all();

        // Lấy danh sách ID các tag đã chọn trước đó để hiển thị checked trên view
        $oldTags = $event->tags->pluck('id')->toArray();

        return view('events.edit', compact('event', 'categories', 'tags', 'oldTags'));
    }

    // Cập nhật dữ liệu chỉnh sửa vào Database
    public function update(EventRequest $request, Event $event)
    {
        $this->authorize('update', $event);

        $data = $request->validated();

        // Xử lý thay đổi ảnh banner mới (Xóa ảnh cũ nếu có để tránh rác server)
        if ($request->hasFile('banner')) {
            if ($event->banner) {
                Storage::disk('public')->delete($event->banner);
            }
            $path = $request->file('banner')->store('events/banners', 'public');
            $data['banner'] = $path;
        }

        $event->update($data);

        // Đồng bộ lại danh sách thẻ (Tags) mới nhất
        $event->tags()->sync($request->input('tags', []));

        return redirect()->route('events.show', $event->id)
            ->with('success', 'Thông tin sự kiện đã được cập nhật thành công.');
    }

    // Xóa sự kiện (Áp dụng SoftDeletes - Xóa mềm)
    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);

        // Bản ghi được xóa mềm qua Eloquent SoftDeletes để lưu vết lịch sử
        $event->delete();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Sự kiện đã được chuyển vào thùng rác thành công.');
    }
}