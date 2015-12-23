<?php

namespace packages\wilgucki\Csv\tests;

use Illuminate\Support\Facades\Config;
use Wilgucki\Csv\CsvCollection;

class CsvCollectionTest extends \TestCase
{
    public function testToCsv()
    {
        Config::shouldReceive('get')
            ->times(4)
            ->with('csv.delimiter')
            ->andReturn(';');

        Config::shouldReceive('get')
            ->times(4)
            ->with('csv.enclosure')
            ->andReturn('"');

        Config::shouldReceive('get')
            ->times(4)
            ->with('csv.escape')
            ->andReturn('\\');

        Config::shouldReceive('get')
            ->times(6)
            ->with('csv.encoding.writer.enabled')
            ->andReturn('false');

        $collection = new CsvCollection([['x' => 'a', 'y' => 'b', 'z' => 'c']]);
        $this->assertCount(3, explode(PHP_EOL, $collection->toCsv()));

        $collection = new CsvCollection([['x' => 'a', 'y' => 'b', 'z' => 'c']]);
        $this->assertCount(2, explode(PHP_EOL, $collection->toCsv(false)));

        $collection = new CsvCollection([['a', 'b', 'c']]);
        $this->assertCount(3, explode(PHP_EOL, $collection->toCsv()));

        $collection = new CsvCollection([['a', 'b', 'c']]);
        $this->assertCount(2, explode(PHP_EOL, $collection->toCsv(false)));
    }
}
