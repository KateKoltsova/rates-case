<?php

namespace App\Providers;

use App\Services\PrivatRates;
use App\Services\RatesAbstract;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(RatesAbstract::class, PrivatRates::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
