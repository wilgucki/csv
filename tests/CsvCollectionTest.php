<?php
use Tests\TestCase;
use Wilgucki\Csv\CsvCollection;
use Wilgucki\Csv\Traits\CsvCustomCollection;

class CsvCollectionTest extends TestCase
{
    /**
     * @dataProvider csvDataProvider
     */
    public function testToCsv(array $data, bool $header, int $expectedCount)
    {
        $collection = new CsvCollection($data);
        static::assertCount($expectedCount, explode(PHP_EOL, $collection->toCsv($header)));
    }

    public function testCsvCollectionTrait()
    {
        $mock = $this->getMockForTrait(CsvCustomCollection::class);
        static::assertInstanceOf(CsvCollection::class, $mock->newCollection());
    }

    public function csvDataProvider()
    {
        return [
            [
                [['x' => 'a', 'y' => 'b', 'z' => 'c']],
                true,
                3
            ],
            [
                [['x' => 'a', 'y' => 'b', 'z' => 'c'], ['x' => 'a', 'y' => 'b', 'z' => 'c']],
                true,
                4
            ],
            [
                ['x' => 'a', 'y' => 'b', 'z' => 'c'],
                true,
                3
            ],
            [
                [['x' => 'a', 'y' => 'b', 'z' => 'c'], ['x' => 'a', 'y' => 'b', 'z' => 'c']],
                false,
                3
            ],
            [
                ['x' => 'a', 'y' => 'b', 'z' => 'c'],
                false,
                2
            ],
            [
                [['a', 'b', 'c']],
                true,
                3
            ],
            [
                [['a', 'b', 'c'], ['a', 'b', 'c']],
                true,
                4
            ],
            [
                [['a', 'b', 'c']],
                false,
                2
            ],
            [
                [['a', 'b', 'c'], ['a', 'b', 'c']],
                false,
                3
            ],
            [
                [],
                true,
                1,
            ],
            [
                [],
                false,
                1,
            ],
        ];
    }
}
