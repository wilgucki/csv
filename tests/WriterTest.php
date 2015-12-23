<?php

namespace packages\wilgucki\Csv\tests;

use org\bovigo\vfs\vfsStream;
use Wilgucki\Csv\Writer;

class WriterTest extends \TestCase
{
    protected $fs;

    public function setUp()
    {
        parent::setUp();
        $this->fs = vfsStream::setup('files');
    }

    public function testCreateCsv()
    {
        $writer = new Writer();
        $writer->create(vfsStream::url('files/file.csv'), ',', '"', '\\');
        $writer->close();
        $this->assertFileExists(vfsStream::url('files/file.csv'));
    }

    public function testWriteLine()
    {
        $writer = new Writer();
        $writer->create(vfsStream::url('files/file2.csv'), ',', '"', '\\');
        $writer->writeLine(['cell1', 'cell2']);
        $writer->close();
        $content = file_get_contents(vfsStream::url('files/file2.csv'));
        $this->assertEquals('cell1,cell2'.PHP_EOL, $content);
    }

    public function testWriteLines()
    {
        $writer = new Writer();
        $writer->create(vfsStream::url('files/file3.csv'), ',', '"', '\\');
        $writer->writeAll([['header1', 'header2'], ['cell1', 'cell2']]);
        $writer->close();
        $file = file(vfsStream::url('files/file3.csv'));
        $this->assertCount(2, $file);
    }

    public function testFlush()
    {
        $writer = new Writer();
        $writer->create(vfsStream::url('files/file4.csv'), ',', '"', '\\');
        $writer->writeAll([['header1', 'header2'], ['cell1', 'cell2']]);
        $data = $writer->flush();
        $writer->close();
        $this->assertTrue(is_string($data));
        $this->assertStringEqualsFile(vfsStream::url('files/file4.csv'), $data);
    }
}
