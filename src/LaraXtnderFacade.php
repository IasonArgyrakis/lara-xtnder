<?php

namespace IasonArgyrakis\LaraXtnder;

use Illuminate\Support\Facades\Facade;

/**
 * @see \IasonArgyrakis\LaraXtnder\Skeleton\SkeletonClass
 */
class LaraXtnderFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'lara-xtnder';
    }
}
