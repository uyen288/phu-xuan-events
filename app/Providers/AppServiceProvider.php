<?php

namespace App\Providers;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use App\Policies\EventPolicy;
use App\Policies\RegistrationPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Danh sách Policy được đăng ký thủ công.
     */
    protected $policies = [
        Event::class        => EventPolicy::class,
        Registration::class => RegistrationPolicy::class,
        User::class         => UserPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
