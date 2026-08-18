<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Blade directive untuk role
        Blade::if('role', function ($role) {
            return auth()->check() && auth()->user()->role === $role;
        });
    }

    public function register(): void
    {
        //
    }
}