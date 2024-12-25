<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

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
        //
        Livewire::setScriptRoute(function ($handle) {
            return Route::get('/livewire/livewire-js', $handle);
        });
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
