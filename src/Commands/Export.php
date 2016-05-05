<?php

namespace Wilgucki\Csv\Commands;

use Illuminate\Console\Command;

class Export extends Command
{
    protected $signature = 'csv:export {model} {csv-file}';

    protected $description = 'Export database table into CSV file';

    public function handle()
    {
        $modelClass = $this->argument('model');
        $csvPath = $this->argument('csv-file');

        $traits = class_uses($modelClass);
        if (!isset($traits['Wilgucki\Csv\Traits\CsvCustomCollection'])) {
            $this->error('Your model class does not use CsvCustomCollection trait.');
            return;
        }

        file_put_contents(base_path($csvPath), $modelClass::all()->toCsv());

        $this->info('Done!');
    }
}
