<?php

namespace Litstack\Wikipedia\Facades;

use Illuminate\Support\Facades\Facade;

class Wikipedia extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'wikipedia';
    }
}
