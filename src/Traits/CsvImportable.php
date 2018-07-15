<?php
namespace Wilgucki\Csv\Traits;

use Wilgucki\PhpCsv\Reader;

trait CsvImportable
{
    public static function fromCsv(string $file)
    {
        /**
         * @var Reader $reader
         */
        $reader = app()->get('csv-reader');
        $reader = $reader->open($file);
        $reader->getHeader();
        while (($row = $reader->readLine()) !== false) {
            $id = isset($row['id']) ? $row['id'] : null;
            $model = self::findOrNew($id);
            foreach ($row as $column => $value) {
                if ($column == 'id') {
                    continue;
                }
                if (!in_array($column, $model->fillable)) {
                    continue;
                }
                $model->{$column} = $value;
            }
            $model->save();
        }
    }
}
