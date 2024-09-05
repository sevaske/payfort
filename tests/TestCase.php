<?php

namespace Sevaske\Payfort\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Sevaske\Payfort\PayfortServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            PayfortServiceProvider::class,
        ];
    }
}
