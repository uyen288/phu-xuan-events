<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    /**
     * M3.1: Đăng ký tham gia sự kiện (Student)
     * Tách ra khỏi DashboardController để đúng SRP.
     */
    public function store(Request $request, $eventId)
    {
        $userId = Auth::id();
        $event  = Event::with('registrations')->findOrFail($eventId);

        // 1. Kiểm tra đã đăng ký chưa
        $alreadyRegistered = Registration::where('user_id', $userId)
            ->where('event_id', $eventId)
            ->exists();

        if ($alreadyRegistered) {
            return redirect()->back()
                ->with('error', 'Bạn đã đăng ký tham gia sự kiện này rồi!');
        }

        // 2. Kiểm tra còn chỗ không
        $confirmedCount = $event->registrations()
            ->where('status', 'confirmed')
            ->count();

        if ($confirmedCount >= $event->capacity) {
            return redirect()->back()
                ->with('error', 'Rất tiếc, sự kiện này đã đầy chỗ!');
        }

        // 3. Kiểm tra sự kiện có đang mở đăng ký không
        if ($event->status !== 'published') {
            return redirect()->back()
                ->with('error', 'Sự kiện này hiện không mở đăng ký.');
        }

        // 4. Tạo đơn đăng ký
        Registration::create([
            'user_id'  => $userId,
            'event_id' => $eventId,
            'status'   => 'pending',
            'note'     => $request->input('note'),
        ]);

        return redirect()->back()
            ->with('success', 'Đăng ký thành công! Vui lòng chờ Ban tổ chức phê duyệt.');
    }

    /**
     * M3.2: Hủy đăng ký tham gia sự kiện (Student)
     */
    public function cancel($eventId)
    {
        $registration = Registration::where('user_id', Auth::id())
            ->where('event_id', $eventId)
            ->firstOrFail();

        // Chỉ cho hủy khi còn đang chờ duyệt
        if ($registration->status === 'confirmed') {
            return redirect()->back()
                ->with('error', 'Không thể hủy đăng ký đã được duyệt. Vui lòng liên hệ Ban tổ chức.');
        }

        $registration->delete();

        return redirect()->back()
            ->with('success', 'Đã hủy đăng ký thành công.');
    }

    /**
     * M3.5: Trang "Sự kiện của tôi" — danh sách sự kiện đã đăng ký (Student)
     */
    public function myEvents()
    {
        $registrations = Registration::with(['event.category', 'event.tags'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('registrations.my-events', compact('registrations'));
    }

    /**
     * M3.3: Danh sách người đăng ký sự kiện (Organizer xem)
     */
    public function eventRegistrations(Event $event)
    {
        $this->authorize('update', $event); // Organizer/Admin mới xem được

        $registrations = Registration::with('user')
            ->where('event_id', $event->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('events.registrations', compact('event', 'registrations'));
    }
}
