<?php

namespace packages\wilgucki\Csv\tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamFile;
use Wilgucki\Csv\Traits\CsvImportable;
use Wilgucki\Csv\Writer;

class CsvImportableTest extends \TestCase
{
    use DatabaseMigrations;

    protected $vfsRoot;

    public function setUp()
    {
        parent::setUp();

        $this->vfsRoot = vfsStream::setup('files');
        $this->vfsRoot->addChild(new vfsStreamFile('file1.csv'));
        $this->vfsRoot->addChild(new vfsStreamFile('file2.csv'));
    }

    public function testFromCsvInsert()
    {
        $userData = ['name' => 'abc', 'email' => 'abc@abc.com', 'password' => 'pass'];

        $writer = new Writer();
        $writer->create($this->vfsRoot->getChild('file1.csv')->url());
        $writer->writeLine(array_keys($userData));
        $writer->writeLine($userData);
        $writer->close();

        $model = new class extends Model
        {
            use CsvImportable;
            protected $table = 'users';
            protected $fillable = ['name', 'email', 'password'];
        };
        $model->fromCsv($this->vfsRoot->getChild('file1.csv')->url());

        $item = $model->first();

        $this->assertEquals($userData['name'], $item->name);
        $this->assertEquals($userData['email'], $item->email);
        $this->assertEquals($userData['password'], $item->password);
    }

    public function testFromCsvUpdate()
    {
        $userData = ['id' => 1, 'name' => 'abc', 'email' => 'abc@abc.com', 'password' => 'pass'];

        $writer = new Writer();
        $writer->create($this->vfsRoot->getChild('file2.csv')->url());
        $writer->writeLine(array_keys($userData));
        $writer->writeLine($userData);
        $writer->close();

        $model = new class extends Model
        {
            use CsvImportable;
            protected $table = 'users';
            protected $fillable = ['name', 'email', 'password'];
        };

        $model->name = 'test';
        $model->email = 'test@test.com';
        $model->password = 'test';
        $model->save();

        $model->fromCsv($this->vfsRoot->getChild('file2.csv')->url());

        $item = $model->first();

        $this->assertEquals($userData['name'], $item->name);
        $this->assertEquals($userData['email'], $item->email);
        $this->assertEquals($userData['password'], $item->password);
    }
}
