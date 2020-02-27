<?php

namespace App\Providers;

use App\Policies\ReplyPolicy;
use App\Policies\ThreadPolicy;
use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Thread::class => ThreadPolicy::class,
        Reply::class => ReplyPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::before(function (User $user) {
            if ($user->name === 'super_admin') {
                return true;
            }
        });
    }
}
