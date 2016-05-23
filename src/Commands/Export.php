<?php

namespace Wilgucki\Csv\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class Export extends Command
{
    protected $name = 'csv:export';
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

    protected function getArguments()
    {
        return [
            ['model', InputArgument::REQUIRED, 'Model\'s class name with its namespace'],
            ['csv-file', InputArgument::REQUIRED, 'File name with path relative to project\'s root directory']
        ];
    }
}
