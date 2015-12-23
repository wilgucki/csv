<?php

namespace Wilgucki\Csv\Traits;

use Wilgucki\Csv\Writer;

trait CsvExportable
{
    public function toCsv()
    {
        $writer = new Writer();
        $writer->create();
        $data = $this->toArray();
        $writer->writeLine(array_keys($data));
        $writer->writeLine($data);
        $out = $writer->flush();
        $writer->close();
        return $out;
    }
}
