<?php

namespace App\Http\Controllers;

use App\Services\ImageService;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function getFile($filename)
    {
        return response()->file(base_path() . ImageService::PATH_PRIVATE . $filename);
    }

    public function getExport($filename)
    {
        return response()->file(base_path() . '/storage/app/private/exports/' . $filename);
    }

    public function getImport($filename)
    {
        return Storage::download($filename);
    }
}
