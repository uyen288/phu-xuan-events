<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RegistrationResource;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    /**
     * M4.4: POST /api/v1/registrations - Đăng ký sự kiện qua API (Sanctum auth)
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => ['required', 'integer', 'exists:events,id'],
            'note'     => ['nullable', 'string', 'max:1000'],
        ], [
            'event_id.required' => 'Vui lòng chọn sự kiện.',
            'event_id.exists'   => 'Sự kiện không tồn tại.',
        ]);

        $userId  = Auth::id();
        $eventId = $request->event_id;
        $event   = Event::with('registrations')->findOrFail($eventId);

        // Kiểm tra sự kiện có published không
        if ($event->status !== 'published') {
            return response()->json([
                'success' => false,
                'message' => 'Sự kiện này hiện không mở đăng ký.',
            ], 400);
        }

        // Kiểm tra đã đăng ký chưa
        $alreadyRegistered = Registration::where('user_id', $userId)
            ->where('event_id', $eventId)
            ->exists();

        if ($alreadyRegistered) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã đăng ký sự kiện này rồi.',
            ], 422);
        }

        // Kiểm tra còn chỗ không
        $confirmedCount = $event->registrations()
            ->where('status', 'confirmed')
            ->count();

        if ($confirmedCount >= $event->capacity) {
            return response()->json([
                'success' => false,
                'message' => 'Sự kiện đã đầy chỗ.',
            ], 422);
        }

        // Tạo đơn đăng ký
        $registration = Registration::create([
            'user_id'  => $userId,
            'event_id' => $eventId,
            'status'   => 'pending',
            'note'     => $request->input('note'),
        ]);

        return response()->json([
            'success' => true,
            'data'    => new RegistrationResource($registration->load(['event', 'user'])),
        ], 201);
    }

    /**
     * M4.5: GET /api/v1/user/registrations - Danh sách đăng ký của user hiện tại
     */
    public function index()
    {
        $registrations = Registration::with(['event.category', 'event.tags'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data'    => RegistrationResource::collection($registrations),
            'meta'    => [
                'current_page' => $registrations->currentPage(),
                'last_page'    => $registrations->lastPage(),
                'total'        => $registrations->total(),
            ],
        ]);
    }
}
