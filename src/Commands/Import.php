<?php

namespace Wilgucki\Csv\Commands;

use Illuminate\Console\Command;

class Import extends Command
{
    protected $signature = 'csv:import {model} {csv-file}';

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
}
