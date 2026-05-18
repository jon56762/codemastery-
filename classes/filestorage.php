<?php

class FileStorage
{
    private $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
        // Make sure the data directory exists
        if (!file_exists(DATA_PATH)) {
            mkdir(DATA_PATH, 0777, true);
        }
    }

    // ---- Replace getFromFile ----
    private function getFileContents()
    {
        $filePath = DATA_PATH . $this->filename;
        if (!file_exists($filePath)) {
            // Create empty file
            file_put_contents($filePath, json_encode([]));
            return [];
        }
        $content = file_get_contents($filePath);
        return json_decode($content, true) ?? [];
    }

    // ---- Replace saveToFile ----
    private function putFileContents($data)
    {
        $filePath = DATA_PATH . $this->filename;
        return file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));
    }

    // Now update your public methods to use these
    public function readAll()
    {
        return $this->getFileContents();
    }

    public function writeAll($data)
    {
        return $this->putFileContents($data);
    }

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
