<?php

namespace App\Providers;

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
        if ($this->app->runningInConsole()) {
            $phase4SeederPath = base_path('database/seeds/Phase4CoreDefaultsSeeder.php');

            if (file_exists($phase4SeederPath)) {
                require_once $phase4SeederPath;
            }
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
