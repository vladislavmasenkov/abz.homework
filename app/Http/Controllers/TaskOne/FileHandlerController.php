<?php

namespace App\Http\Controllers\TaskOne;

use App\SystemFile;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FileHandlerController extends Controller
{
    public function index()
    {
        return view('taskone/index');
    }


    public function uploadFile(Request $request)
    {
        $this->validate($request,[
            'handling_file' => 'required|file'
        ]);
        $handlingFile = $request->file('handling_file');

        if ($handlingFile && in_array($handlingFile->getClientOriginalExtension(),['json','xml','csv'])) {
            $fileName = str_random(32) . '.' . $handlingFile->getClientOriginalExtension();
            $filePath = $handlingFile->storeAs('upload/' . $handlingFile->getClientOriginalExtension(), $fileName);
            $systemFile = SystemFile::create([
                'storage_path' => $filePath,
                'filename' => $fileName,
                'extension' => $handlingFile->getClientOriginalExtension()
            ]);
            return response()->json(['file_id' => $systemFile->id, 'file_name' => $systemFile->filename]);
        }
        return response()->json(['message' => 'File not uploaded'], 400);
    }

    public function getFileData($id)
    {
        $fileInfo = SystemFile::find($id);
        if ($fileInfo) {
            $fileHandler = $this->getFileHandler($fileInfo->extension);
            $fileHandler->loadFile(storage_path('app' . DIRECTORY_SEPARATOR . $fileInfo->storage_path));
            $fileData = $fileHandler->getData();
            return view('taskone/filedataview', ['filedata' => $fileData]);
        }
        return view('taskone/filenotfound');
    }

    public function downloadFile($id)
    {
        $fileInfo = SystemFile::find($id);
        if ($fileInfo) {
            $fileHandler = $this->getFileHandler($fileInfo->extension);
            $headers = [
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Content-Disposition' => 'attachment; filename='.str_random(32).'.'.$fileInfo->extension,
                'Expires' => '0',
                'Pragma' => 'public'
            ];
            $callback = function () use ($fileHandler, $fileInfo) {
                $fileHandler->getFile(storage_path('app' . DIRECTORY_SEPARATOR . $fileInfo->storage_path));
            };
            return response()->stream($callback, 200, $headers);
        }
        return response()->json(['message' => 'File not found'], 400);
    }

    protected function getFileHandler($mimeType)
    {
        $fileHandler = null;
        switch ($mimeType) {
            case 'xml':
                $fileHandler = new XmlFileHandler();
                break;
            case 'json':
                $fileHandler = new JsonFileHandler();
                break;
            case 'csv':
                $fileHandler = new CsvFileHandler();
                break;
            default :
                $fileHandler = new XmlFileHandler();
        }
        return $fileHandler;
    }
}
