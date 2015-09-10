<?php

namespace Wilgucki\Csv\Facades;

use Illuminate\Support\Facades\Facade;

class Writer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'writer';
    }
}
