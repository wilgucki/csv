<?php

namespace packages\wilgucki\Csv\tests;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamFile;
use Wilgucki\Csv\Reader;

class ReaderTest extends \TestCase
{
    protected $filePath;

    public function setUp()
    {
        parent::setUp();
        $root = vfsStream::setup('files');
        $root->addChild(new vfsStreamFile('file.csv'));
        $this->filePath = $root->getChild('file.csv')->url();

        $data = [
            'header1,header2',
            'value1,value2',
            'valueA,valueB',
        ];

        file_put_contents($this->filePath, implode(PHP_EOL, $data));
    }

    /**
     * @expectedException \ErrorException
     */
    public function testOpenInvalidFile()
    {
        $reader = new Reader();
        $reader->open('/invalid/file/'.md5(uniqid().microtime()).'.csv');
    }

    public function testOpenValidFile()
    {
        $reader = new Reader();
        $open = $reader->open($this->filePath);
        $reader->close();
        $this->assertTrue($open instanceof Reader);
    }

    public function testGetHeader()
    {
        $reader = new Reader();
        $reader->open($this->filePath, ',', '"', '\\');
        $header = $reader->getHeader($this->filePath);
        $reader->close();
        $this->assertArraySubset($header, ['header1', 'header2']);
    }

    public function testGetLineWithoutHeader()
    {
        $reader = new Reader();
        $reader->open($this->filePath, ',', '"', '\\');
        $line = $reader->readLine();
        $this->assertArraySubset($line, ['header1', 'header2']);
        $line = $reader->readLine();
        $this->assertArraySubset($line, ['value1', 'value2']);
        $line = $reader->readLine();
        $this->assertArraySubset($line, ['valueA', 'valueB']);
        $reader->close();
    }

    public function testGetLineWithHeader()
    {
        $reader = new Reader();
        $reader->open($this->filePath, ',', '"', '\\');
        $reader->getHeader();
        $line = $reader->readLine();
        $reader->close();
        $this->assertArraySubset($line, ['header1' => 'value1', 'header2' => 'value2']);
    }

    public function testReadAll()
    {
        $reader = new Reader();
        $reader->open($this->filePath, ',', '"', '\\');
        $data = $reader->readAll();
        $reader->close();
        $this->assertTrue(is_array($data));
    }
}
