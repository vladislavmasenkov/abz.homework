<?php

namespace App\Http\Controllers\TaskOne;

use Illuminate\Support\Facades\Storage;

class XmlFileHandler implements IFileHandler
{
    private $xml = null;
    private $data = null;

    public function getData()
    {
        if ($this->xml) {
            $simpleXml = new \SimpleXMLElement($this->xml);
            $this->data = $this->xmlToArray($simpleXml);
            return $this->data;
        }
        return false;
    }

    protected function xmlToArray($object)
    {
        $result = $object;
        if (is_object($object)) {
            $result = get_object_vars($object);
        }
        if (is_array($result)) {
            foreach ($result as $key => $item) {
                $result[strtolower($key)] = $this->xmlToArray($item);
                if (!is_numeric($key)) {
                    unset($result[$key]);
                }
            }
        }
        return $result;
    }

    protected function arrayToXml(\XMLWriter $xml, $array, $rootNodeName = 'CURRENCIES')
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if (is_numeric($key)) {
                    $xml->startElement(strtoupper($rootNodeName));
                    $this->arrayToXml($xml, $value, $rootNodeName);
                    $xml->endElement();
                } else {
                    $this->arrayToXml($xml, $value, $key);
                }
            } else {
                $xml->writeElement(strtoupper($key), $value);
            }
        }
    }

    public function loadFile($filepath)
    {
        if (is_file($filepath)) {
            $this->xml = file_get_contents($filepath);
        }
    }

    public function getFile($filepath)
    {
        $this->loadFile($filepath);
        if ($this->xml) {
            $fileData = $this->getData();
            $fileData['last_update'] = date('Y-m-d');
            $fileData['currency'] = CurrencyService::updateCurrencyRate($fileData['currency']);

            $xmlWriter = new \XMLWriter();
            $xmlWriter->openMemory();
            $xmlWriter->startDocument('1.0', 'iso-8859-8', 'yes');
            $xmlWriter->startElement('CURRENCIES');
            $this->arrayToXml($xmlWriter,$fileData);
            $xmlWriter->endElement();
            $xmlWriter->endDocument();
            $xml = $xmlWriter->outputMemory();

            $file = fopen('php://output','w');
            fputs($file,$xml);
            fclose($file);
        }
        return false;
    }
}
