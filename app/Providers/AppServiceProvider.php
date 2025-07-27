<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
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
       RateLimiter::for('auth', function (Request $request) {
            return $request->user()
                ? Limit::perMinute(3)->by($request->user()->id)
                : Limit::perMinute(3)->by($request->ip());
        });

        Gate::define('admin', function (User $user) {
            return $user->role == 'admin';
        });
    }
}
