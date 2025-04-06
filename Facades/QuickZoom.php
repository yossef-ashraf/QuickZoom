<?php

namespace YourVendor\QuickZoom\Facades;

use Illuminate\Support\Facades\Facade;

class QuickZoom extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'quickzoom';
    }
}