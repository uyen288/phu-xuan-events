<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * M4.1: GET /api/v1/events - Danh sách sự kiện có filter
     */
    public function index(Request $request)
    {
        $query = Event::with(['category', 'tags', 'user'])
            ->withCount('registrations')
            ->published()
            ->upcoming();

        // Lọc theo category_id
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Lọc theo tag (ID)
        if ($request->filled('tag_id')) {
            $query->whereHas('tags', fn($q) => $q->where('tags.id', $request->tag_id));
        }

        // Tìm kiếm LIKE (M2.6)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('location', 'LIKE', "%{$search}%");
            });
        }

        $events = $query->orderBy('start_time', 'asc')->paginate(12);

        return response()->json([
            'success' => true,
            'data'    => EventResource::collection($events),
            'meta'    => [
                'current_page' => $events->currentPage(),
                'last_page'    => $events->lastPage(),
                'total'        => $events->total(),
                'per_page'     => $events->perPage(),
            ],
        ]);
    }

    /**
     * M4.2: GET /api/v1/events/{id} - Chi tiết sự kiện
     */
    public function show(Event $event)
    {
        // Chỉ cho xem sự kiện đã published
        if ($event->status !== 'published') {
            return response()->json([
                'success' => false,
                'message' => 'Sự kiện không tồn tại hoặc chưa được công khai.',
            ], 404);
        }

        $event->load(['category', 'tags', 'user', 'registrations']);

        return response()->json([
            'success' => true,
            'data'    => new EventResource($event),
        ]);
    }

    public function store(Request $request, Event $event)
    {
        return response()->json([
            'success' => true,
            'message' => 'POST thành công',
            'event' => $event,
            'request' => $request->all(),
        ]);
    }
}
