<?php

namespace Coderscoop\Laravelpaypal;

use Illuminate\Support\ServiceProvider;

require __DIR__ . '/../vendor/autoload.php';


class PaypalServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        include __DIR__.'/routes.php';

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
