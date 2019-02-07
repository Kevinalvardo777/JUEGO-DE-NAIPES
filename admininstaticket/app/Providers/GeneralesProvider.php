<?php

namespace Instaticket\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;


class GeneralesProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
         View::composer('*', 'Instaticket\Http\ViewComposers\GeneralComposer');
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
