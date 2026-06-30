<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;

class RegistrationPolicy
{
    /**
     * Student chỉ được xem/hủy registration của chính mình.
     * Admin có thể làm tất cả.
     */
    public function delete(User $user, Registration $registration): bool
    {
        return $user->isAdmin() || $user->id === $registration->user_id;
    }

    public function view(User $user, Registration $registration): bool
    {
        return $user->isAdmin()
            || $user->id === $registration->user_id
            || $user->id === $registration->event->user_id;
    }
}
