<?php
use Tests\TestCase;
use Wilgucki\Csv\CsvServiceProvider;
use Wilgucki\PhpCsv\Reader;
use Wilgucki\PhpCsv\Writer;

class CsvServiceProviderTest extends TestCase
{
    public function testRegister()
    {
        $provider = new CsvServiceProvider(app());
        $provider->register();

        static::assertInstanceOf(Reader::class, app()->get('csv-reader'));
        static::assertInstanceOf(Writer::class, app()->get('csv-writer'));
    }
}
