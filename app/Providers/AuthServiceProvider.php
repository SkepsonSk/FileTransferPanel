<?php

namespace App\Providers;

use App\Policies\PowersPolicy;
use App\Policies\ServerPolicy;
use App\Policies\UsersPolicy;
use App\Power;
use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Server::class => ServerPolicy::class,
        Power::class => PowersPolicy::class,
        User::class => UsersPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
