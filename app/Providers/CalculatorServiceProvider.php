<?php

namespace App\Providers;

use App\Services\CalculatorService;
use Illuminate\Support\ServiceProvider;

class CalculatorServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CalculatorService::class, function ($app) {
            return new CalculatorService();
        });
    }
}
