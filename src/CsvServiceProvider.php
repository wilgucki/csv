<?php

namespace Wilgucki\Csv;

use Illuminate\Support\ServiceProvider;

class CsvServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register()
    {
        \App::bind('reader', function () {
            return new Reader();
        });

        \App::bind('writer', function () {
            return new Writer();
        });
    }
}
