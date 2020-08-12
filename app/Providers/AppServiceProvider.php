<?php

namespace App\Providers;

use App\Services\Click108HoroscopeScraper;
use App\Services\Contracts\HoroscopeScraper;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(HoroscopeScraper::class, Click108HoroscopeScraper::class);
    }
}
