<?php

namespace App\Providers;

use App\Interfaces\IDateChecker;
use App\Services\DateCheckerService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     */
    public function register(): void {
        $this->app->bind(IDateChecker::class, DateCheckerService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        //
    }
}
