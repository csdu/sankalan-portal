<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Telescope\TelescopeServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Paginator::defaultView('partials.pagination');
        View::composer('dashboard', function($view) {
            return $view->with(['signedInUser' => auth()->user()->load('teams')]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if( $this->app->isLocal()) {
            $this->app->register(TelescopeServiceProvider::class);
        }
    }
}
