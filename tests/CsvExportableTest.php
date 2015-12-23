<?php

namespace packages\wilgucki\Csv\tests;

class CsvExportableTest extends \TestCase
{
    public function testToCsv()
    {
        $mock = $this->getMockForTrait('Wilgucki\Csv\Traits\CsvExportable', [], '', true, true, true, ['toArray']);
        $mock->expects($this->once())
             ->method('toArray')
             ->will($this->returnValue(['id' => 1, 'name' => 'abc']));
        $output = $mock->toCsv();
        $this->assertEquals('id,name'.PHP_EOL.'1,abc'.PHP_EOL, $output);
    }
}
