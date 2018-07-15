<?php
namespace Wilgucki\Csv;

use Illuminate\Database\Eloquent\Collection;

class CsvCollection extends Collection
{
    public function toCsv(bool $setHeader = true, bool $append = false)
    {
        $data = $this->toArray();

        if (empty($data)) {
            return '';
        }

        $writer = app()->get('csv-writer');
        $writer->create('php://memory', $append ? 'a+' : 'w+');

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
