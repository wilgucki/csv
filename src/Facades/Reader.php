<?php

namespace Wilgucki\Csv\Facades;

use Illuminate\Support\Facades\Facade;

class Reader extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'reader';
    }
}
