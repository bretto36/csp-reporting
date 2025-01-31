<?php

namespace Bretto36\CspReporting\Tests;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app): array
    {
        return ['Bretto36\CspReporting\ServiceProvider'];
    }
}
