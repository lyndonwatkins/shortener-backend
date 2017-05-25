<?php

namespace App\Providers;

use App\Link;
use App\Observers\LinkObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Link::observe(LinkObserver::class);
    }
}
