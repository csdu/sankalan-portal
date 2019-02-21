<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Telescope\TelescopeServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

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

        View::composer(['events.*', 'teams.*', 'dashboard'], function($view) {
            return $view->with([
                'signedInUser' => optional(auth()->user())->load('teams')
            ]);
        });
        
        Collection::macro('recursive', function () {
            return $this->map(function ($value) {
                if (is_array($value) || is_object($value)) {
                    return collect($value)->recursive();
                }
                return $value;
            });
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
