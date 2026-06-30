<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    public function viewAny(?User $user): bool
    {
        return true; // Tất cả mọi người (kể cả khách) đều xem được danh sách sự kiện công khai
    }

    public function view(?User $user, Event $event): bool
    {
        if ($event->status === 'published') {
            return true;
        }
        // Nếu là bản nháp (draft), chỉ Admin hoặc chính người tạo (Owner) mới được xem
        return $user && ($user->role === 'admin' || $user->id === $event->user_id);
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'organizer';
    }

    public function update(User $user, Event $event): bool
    {
        // Admin hoặc chính chủ sở hữu sự kiện mới được quyền sửa
        return $user->role === 'admin' || $user->id === $event->user_id;
    }

    public function delete(User $user, Event $event): bool
    {
        // Nếu là Admin thì được xóa thẳng. 
        // Nếu là Organizer (Chính chủ), chỉ được xóa khi sự kiện đó CHƯA CÓ sinh viên nào đăng ký đơn tham gia
        if ($user->role === 'admin') {
            return true;
        }

        return $user->id === $event->user_id && $event->registrations()->count() === 0;
    }
}