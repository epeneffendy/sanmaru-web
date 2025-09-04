<?php

namespace App\Http\Controllers\Api;
use App\Helpers\ImageHelper;
use App\Services\ImageService;
use App\Http\Controllers\Controller;

class ApiImageController extends Controller
{
    public function show($filename)
    {
       ImageHelper::getImage($filename);
    }
}
