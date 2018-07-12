<?php

namespace App\Http\Controllers\TaskTwo;

use App\Http\Services\ImageHandler;
use App\SystemFile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

/**
 * Class Images
 * @package App\Http\Controllers\TaskTwo
 */
class Images extends Controller
{

    /**
     * Return page with form for uploading image
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->view('tasktwo/index');
    }

    /**
     * Image upload method with native php validation
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function nativeUpload(Request $request)
    {
        $uploadedImage = $request->file('image');
        if (!$uploadedImage) {
            return response()->json(['message' => 'Image is required'], 400);
        }
        $availableTypes = config('app.image_types', [IMAGETYPE_JPEG]);
        list($width, $height, $type) = getimagesize($uploadedImage);

        if (!in_array($type, $availableTypes) || $width !== $height) {
            return response()->json([
                'message' => 'The image has an inaccessible type or ratio'
            ], 400);
        }

        $imageName = str_random(32) . '.' . $uploadedImage->extension();
        if (Storage::disk('uploads')->put('images/' . $imageName, file_get_contents($uploadedImage))) {
            $fileModel = SystemFile::create([
                'storage_path' => public_path('uploads/images'),
                'filename' => $imageName,
                'extension' => $uploadedImage->extension()
            ]);

            return response()->json(['image_id' => $fileModel->id]);
        }

        return response()->json(['message' => 'Some errors with image uploading'], 400);
    }

    /**
     * Image upload method with laravel validation
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function libraryUpload(Request $request)
    {
        $validatedData = $request->validate([
            'image' => 'required|image|dimensions:ratio=1/1'
        ]);
        $availableTypes = config('app.image_types', [IMAGETYPE_JPEG]);
        $imageType = exif_imagetype($validatedData['image']);

        if (!in_array($imageType, $availableTypes)) {
            return response()->json([
                'message' => 'The image has an inaccessible type'
            ], 400);
        }

        $uploadedImage = $validatedData['image'];
        $imageName = str_random(32) . '.' . $uploadedImage->extension();

        if (Storage::disk('uploads')->put('images/' . $imageName, file_get_contents($uploadedImage))) {
            $fileModel = SystemFile::create([
                'storage_path' => public_path('uploads/images'),
                'filename' => $imageName,
                'extension' => $uploadedImage->extension()
            ]);

            return response()->json(['image_id' => $fileModel->id]);
        }
        return response()->json(['message' => 'Some errors with image uploading'], 400);
    }

    /**
     * Get resized images in 4 formats with date watermark
     *
     * @param integer $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getResizeImage($id)
    {
        $fileData = SystemFile::find($id);
        if ($fileData) {
            try {
                $imageHandler = new ImageHandler(public_path('uploads/images/' . $fileData->filename));
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            $dataImages = [];

            $imagestart = microtime(true);
            $dataImages[] = $imageHandler->resize(500, 500)
                ->setTextWatermark(date('d.m.Y H:i:s'), 10, 30)
                ->save(IMAGETYPE_JPEG);
            $dataImages[] = $imageHandler->save(IMAGETYPE_GIF);
            $dataImages[] = $imageHandler->save(IMAGETYPE_PNG);
            $imageend = microtime(true)-$imagestart;

            $imagebmpstart = microtime(true);
            $dataImages[] = $imageHandler->save(IMAGETYPE_BMP);
            $imagebmpend = microtime(true)-$imagebmpstart;

            if ($dataImages) {
                return response()->json([
                    'time' => [$imageend,$imagebmpend,memory_get_peak_usage(true)],
                    'images' => $dataImages
                ]);
            }
            return response()->json(['message' => 'Error with image resizing'], 400);
        }
        return response()->json(['message' => 'File not found'], 400);
    }
}
