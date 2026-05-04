<?php

class FileStorage
{
    private $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    // Read all records
    public function readAll()
    {
        return getFromFile($this->filename);
    }

    // Write all records (overwrites the file)
    public function writeAll($data)
    {
        return saveToFile($this->filename, $data);
    }

    // Find first record where $key == $value
    public function find($key, $value)
    {
        $all = $this->readAll();
        foreach ($all as $record) {
            if (isset($record[$key]) && $record[$key] == $value) {
                return $record;
            }
        }
        return null;
    }

    // Find all records matching a custom filter function
    public function where($callback)
    {
        $all = $this->readAll();
        return array_values(array_filter($all, $callback));
    }

    // Get next ID for a given field (like your old id generator)
    public function nextId($field = 'id')
    {
        $all = $this->readAll();
        $max = 0;
        foreach ($all as $record) {
            if (isset($record[$field]) && $record[$field] > $max) {
                $max = $record[$field];
            }
        }
        return $max + 1;
    }
}