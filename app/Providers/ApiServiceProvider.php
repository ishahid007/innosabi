<?php

namespace App\Providers;

use App\Contracts\ApiService;
use App\Services\InnosabiApiService;
use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('innosabiApi', concrete: function (): ApiService {
            return new InnosabiApiService(
                config('innosabi.api.base_url'),
                config('innosabi.api.username'),
                config('innosabi.api.password')
            );
        });

        $this->app->bind(ApiService::class, 'innosabiApi');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
