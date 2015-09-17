<?php

namespace Wilgucki\Csv;

use Illuminate\Database\Eloquent\Collection;

class CsvCollection extends Collection
{
    public function toCsv()
    {
        $writer = \CsvWriter::create();
        $data = $this->toArray();
        if (isset($data[0])) {
            $writer->writeLine(array_keys($data[0]));
        }
        $writer->writeAll($data);
        $out = $writer->flush();
        $writer->close();
        return $out;
    }
}
