<?php

namespace TotpGenerator\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use TotpGenerator\Providers\TotpServiceProvider;
use TotpGenerator\Facades\Totp;

abstract class TestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->register(TotpServiceProvider::class);
    }

    protected function getPackageProviders($app)
    {
        return [
           TotpServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Totp' => Totp::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('totp.default_period', 30);
        $app['config']->set('totp.default_digits', 6);
        $app['config']->set('totp.window', 1);
    }
}