<?php
namespace Wilgucki\Csv\Traits;

use Wilgucki\PhpCsv\Writer;

trait CsvExportable
{
    public function toCsv(bool $append = false)
    {
        /**
         * @var Writer $writer
         */
        $writer = app()->get('csv-writer');
        $writer->create($append ? 'a+' : 'w+');
        $data = $this->toArray();
        $writer->writeLine(array_keys($data));
        $writer->writeLine($data);
        $out = $writer->flush();
        $writer->close();
        return $out;
    }
}
