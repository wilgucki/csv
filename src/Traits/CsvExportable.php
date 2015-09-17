<?php

namespace Wilgucki\Csv\Traits;

trait CsvExportable
{
    public function toCsv()
    {
        $writer = \CsvWriter::create();
        $data = $this->toArray();
        $writer->writeLine(array_keys($data));
        $writer->writeLine($data);
        $out = $writer->flush();
        $writer->close();
        return $out;
    }
}
