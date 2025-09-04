<?php

namespace App\Http\Controllers\Api;
use App\Helpers\ImageHelper;
use App\Services\ImageService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;

class ApiFileController extends Controller
{
    public function show(Request $request, $filename, ImageService $imageService)
    {
        $user = $request->user();
        if (! $user || ! Helper::isAdminRole($user)) {
            abort(403);
        } 

        $type = (strpos($filename, '.jpg') !== false) ? "image/jpeg" : ((strpos($filename, '.jpeg') !== false) ? "image/jpeg" : ((strpos($filename, '.pdf') !== false) ? 'application/pdf' : "image/png"));

        return response($imageService->getFile($filename))->withHeaders([
            'Content-Type' => $type,
        ]);
    }
}
