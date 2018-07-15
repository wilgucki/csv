<?php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Wilgucki\Csv\Traits\CsvImportable;
use Wilgucki\PhpCsv\Writer;

class CsvImportableTest extends TestCase
{
    use DatabaseMigrations;

    public function testFromCsvInsert()
    {
        $userData = ['name' => 'abc', 'email' => 'abc@abc.com', 'password' => 'pass'];

        $csv = tempnam(sys_get_temp_dir(), 'test-csv-');

        $writer = new Writer();
        $writer->create($csv);
        $writer->writeLine(array_keys($userData));
        $writer->writeLine($userData);
        $writer->close();

        $model = new class extends Model
        {
            use CsvImportable;
            protected $table = 'users';
            protected $fillable = ['name', 'email', 'password'];
        };
        $model->fromCsv($csv);

        $item = $model->first();

        static::assertEquals($userData['name'], $item->name);
        static::assertEquals($userData['email'], $item->email);
        static::assertEquals($userData['password'], $item->password);
    }

    public function testFromCsvUpdate()
    {
        $userData = ['id' => 1, 'name' => 'abc', 'email' => 'abc@abc.com', 'password' => 'pass', 'hidden' => 'abc'];

        $csv = tempnam(sys_get_temp_dir(), 'test-csv-');

        $writer = new Writer();
        $writer->create($csv);
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

        $model->fromCsv($csv);

        $item = $model->first();

        static::assertEquals($userData['name'], $item->name);
        static::assertEquals($userData['email'], $item->email);
        static::assertEquals($userData['password'], $item->password);
        static::assertArrayNotHasKey('hidden', $item->toArray());
    }
}
