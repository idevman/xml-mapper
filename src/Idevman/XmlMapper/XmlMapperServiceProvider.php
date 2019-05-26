<?php

namespace Idevman\XmlMapper;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

/**
 * Provide and initialize Lavavel dependencies like commands
 */
class XmlMapperServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('XmlMapper', function() {
            return new XmlMapper();
        });
    }

}