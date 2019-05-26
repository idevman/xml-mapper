<?php

namespace Tests\Unit;

use Orchestra\Testbench\TestCase as BaseTestCase;

/**
 * Setup base test case to avoid write all things
 */
class TestCase extends BaseTestCase
{

    protected function getPackageProviders($app)
    {
        return [
            \Idevman\XmlMapper\XmlMapperServiceProvider::class
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'XmlMapper' => \Idevman\XmlMapper\Support\Facades\XmlMapper::class,
        ];
    }
    
}