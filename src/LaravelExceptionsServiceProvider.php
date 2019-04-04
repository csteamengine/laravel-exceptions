<?php

namespace Csteamengine\TestComposerPackage;

use Illuminate\Support\ServiceProvider;

class LaravelExceptionsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/laravel-exceptions.php' => config_path('exceptions.php'),
        ]);
//        $this->loadViewsFrom(__DIR__ . '/views', 'ProjectManager');
    }
}
