<?php

namespace Wilgucki\Csv;

use Illuminate\Database\Eloquent\Collection;

class CsvCollection extends Collection
{
    public function toCsv($setHeader = true)
    {
        $writer = new Writer();
        $writer->create();
        $data = $this->toArray();

        if (is_array(array_values($data)[0])) {
            if ($setHeader) {
                $writer->writeLine(array_keys($data[0]));
            }
            $writer->writeAll($data);
        } else {
            if ($setHeader) {
                $writer->writeLine(array_keys($data));
            }
            $writer->writeLine($data);
        }

        $out = $writer->flush();
        $writer->close();
        return $out;
    }
}
