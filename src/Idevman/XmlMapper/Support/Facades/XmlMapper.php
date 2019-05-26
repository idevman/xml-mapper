<?php

namespace Idevman\XmlMapper\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Facade on laravel compatibillity
 */
class XmlMapper extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'XmlMapper';
    }

}