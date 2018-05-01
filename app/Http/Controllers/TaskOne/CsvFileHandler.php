<?php

namespace App\Http\Controllers\TaskOne;

use Illuminate\Support\Facades\Storage;

class CsvFileHandler implements IFileHandler
{
    private $csv = null;
    private $data = null;

    public function getData()
    {
        if ($this->csv) {
            $handle = fopen($this->csv, 'r');
            $keys = fgetcsv($handle, 0, ",");
            while (($line = fgetcsv($handle, 0, ",")) !== FALSE) {
                $this->data['currency'][] = array_combine($keys,$line);
            }
            fclose($handle);
            return $this->data;
        }
        return false;
    }

    public function loadFile($filepath)
    {
        if (is_file($filepath)) {
            $this->csv = $filepath;
        }
    }

    public function getFile($filepath)
    {
        $this->loadFile($filepath);
        if($this->csv) {
            $fileData = $this->getData();
            $fileData['currency'] = CurrencyService::updateCurrencyRate($fileData['currency']);

            $file = fopen('php://output','w');
            fputcsv($file,array_keys($fileData['currency'][0]));
            foreach ($fileData['currency'] as $currency) {
                fputcsv($file,array_values($currency));
            }
            fclose($file);
        }
        return false;
    }
}
