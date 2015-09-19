<?php

namespace Wilgucki\Csv;

/**
 * Class for reading CSV files
 *
 * @package wilgucki/csv
 * @author Maciej Wilgucki <mwilgucki@gmail.com>
 * @license https://github.com/wilgucki/dbrepository/blob/master/LICENSE
 * @link https://github.com/wilgucki/csv
 */
class Reader
{
    protected $handle = null;
    protected $delimiter;
    protected $enclosure;
    protected $escape;
    protected $withHeader = false;
    protected $header = [];

    /**
     * Open CSV file for reading
     *
     * @param      $file File name with path to open
     * @param null $delimiter @link http://php.net/manual/en/function.fgetcsv.php
     * @param null $enclosure @link http://php.net/manual/en/function.fgetcsv.php
     * @param null $escape @link http://php.net/manual/en/function.fgetcsv.php
     *
     * @return $this
     */
    public function open($file, $delimiter = null, $enclosure = null, $escape = null)
    {
        if ($delimiter !== null) {
            $this->delimiter = $delimiter;
        } else {
            $this->delimiter = config('csv.delimiter');
        }

        if ($enclosure !== null) {
            $this->enclosure = $enclosure;
        } else {
            $this->enclosure = config('csv.enclosure');
        }

        if ($escape !== null) {
            $this->escape = $escape;
        } else {
            $this->escape = config('csv.escape');
        }

        $this->handle = fopen($file, 'r+');
        return $this;
    }

    /**
     * Close file pointer
     */
    public function close()
    {
        fclose($this->handle);
    }

    /**
     * Get CSV header. Usually it's a first line in file.
     *
     * @return array
     */
    public function getHeader()
    {
        $this->withHeader = true;
        if (ftell($this->handle) == 0) {
            $this->header = $this->read();
        }
        return $this->header;
    }

    /**
     * Read current line from CSV file
     *
     * @return array
     */
    public function readLine()
    {
        $out = $this->read();
        if ($this->withHeader && is_array($out)) {
            $out = array_combine($this->header, $out);
        }
        return $out;
    }

    /**
     * Read all lines from CSV file
     *
     * @return array
     */
    public function readAll()
    {
        $out = [];
        while (($row = $this->readLine()) !== false) {
            $out[] = $row;
        }
        return $out;
    }

    /**
     * Wrapper for fgetcsv function
     *
     * @return array
     */
    private function read()
    {
        $out = fgetcsv($this->handle, null, $this->delimiter, $this->enclosure);

        if (!is_array($out)) {
            return $out;
        }

        if (config('csv.encoding.reader.enabled') === true) {
            foreach ($out as $k => $v) {
                $out[$k] = iconv(
                    config('csv.encoding.reader.from'),
                    config('csv.encoding.reader.to'),
                    $v
                );
            }
        }

        return $out;
    }
}
