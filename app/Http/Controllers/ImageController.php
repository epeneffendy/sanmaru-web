<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    public function getImage($filename)
    {
        $path = base_path() . ImageService::PATH_PRIVATE . $filename;
        if (!is_file($path) || Str::contains($filename, ['..', '//'])) {
            abort(404);
        }

        // return response()->file($path);

        $type = (strpos($filename, 'jpg') !== false) ? "image/jpeg" : ((strpos($filename, 'jpeg') !== false) ? "image/jpeg" : "image/png");
        header('Content-Type:' . $type);
        header('Content-Length: ' . filesize($path));
        readfile($path);
    }

    public function uploadTempImage(Request $request, ImageService $imageService)
    {
        $data = [];
        try {
            $data = $imageService->uploadTemp($request,'content_image');
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
        return response()->json($data, 200);
    }

    public function uploadImage(Request $request, ImageService $imageService)
    {
        $data = [];
        try {
            $data = $imageService->upload($request['type'], null, $request['content_image']);
            $data = $imageService->getUrl($data);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
        return response()->json($data, 200);
    }
}
