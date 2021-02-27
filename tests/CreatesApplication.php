<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Testing\TestResponse;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        \DB::statement('PRAGMA foreign_keys = ON');

        TestResponse::macro('viewData', function ($key) {
            $this->assertViewHas($key);

            return $this->original->getData()[$key];
        });

        config(['app.pagination.perPage' => 3]);

        return $app;
    }
}
