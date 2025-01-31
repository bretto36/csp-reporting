<?php

namespace Bretto36\CspReporting;

use Illuminate\Support\ServiceProvider as MainServiceProvider;

final class ServiceProvider extends MainServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/csp-reporting.php' => config_path('csp-reporting.php'),
        ]);

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }
}
