<?php

namespace App\Http\Controllers\TaskOne;

use Illuminate\Support\Facades\Storage;

class JsonFileHandler implements IFileHandler
{
    private $json = null;
    private $data = null;

    public function getData() {
        if($this->json) {
            $this->data = json_decode($this->json,true);
            return $this->data;
        }
        return false;
    }

    public function loadFile($filepath) {
        if (is_file($filepath)) {
            $this->json = file_get_contents($filepath);
        }
    }

    public function getFile($filepath)
    {
        $this->loadFile($filepath);
        if($this->json) {
            $fileData = $this->getData();
            $fileData['last_update'] = date('Y-m-d');
            $fileData['currency'] = CurrencyService::updateCurrencyRate($fileData['currency']);

            $file = fopen('php://output','w');
            fputs($file,json_encode($fileData));
            fclose($file);
        }
        return false;
    }
}
