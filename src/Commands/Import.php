<?php

namespace Wilgucki\Csv\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class Import extends Command
{
    protected $name = 'csv:import';
    protected $description = 'Import CSV file into database table';

    public function handle()
    {
        $modelClass = $this->argument('model');
        $csvPath = $this->argument('csv-file');

        $traits = class_uses($modelClass);
        if (!isset($traits['Wilgucki\Csv\Traits\CsvImportable'])) {
            $this->error('Your model class does not use CsvImportable trait.');
            return;
        }

        if (!file_exists(base_path($csvPath))) {
            $this->error('Cannot find csv file.');
            return;
        }

        $modelClass::fromCsv($csvPath);

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
