<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Laravel\Telescope\TelescopeServiceProvider;

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

        View::composer(
            ['events.*', 'teams.*', 'dashboard'],
            fn ($view) => $view->with([
                'signedInUser' => auth()->user(),
            ])
        );

        Collection::macro('recursive', function () {
            return $this->map(
                fn ($value) => is_array($value) || is_object($value)
                    ? collect($value)->recursive()
                    : $value
            );
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(TelescopeServiceProvider::class);
        }
    }
}
