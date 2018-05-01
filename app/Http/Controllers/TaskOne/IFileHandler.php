<?php

namespace App\Http\Controllers\TaskOne;

interface IFileHandler
{
    public function getData();

    public function loadFile($filepath);

    public function getFile($filepath);
}
