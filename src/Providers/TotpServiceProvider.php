<?php

namespace TotpGenerator\Providers;

use Illuminate\Support\ServiceProvider;
use TotpGenerator\Services\TotpGenerator;
use TotpGenerator\Contracts\TotpGeneratorContract;
use TotpGenerator\Facades\Totp as TotpFacade;

class TotpServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Bind the contract to the implementation
        $this->app->singleton(TotpGeneratorContract::class, TotpGenerator::class);

        // Register the facade
        $this->app->singleton('totp', function ($app) {
            return new TotpFacade();
        });

        // Load the configuration file
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/totp.php', 'totp'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish the configuration file
         $this->publishes([
            __DIR__ . '/../Config/totp.php' => config_path('totp.php'),
        ], 'config');
    }
}
