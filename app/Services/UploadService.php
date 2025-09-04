<?php

namespace App\Services;

use App\Traits\ImageHandler;

class UploadService
{
    use ImageHandler;

    public function upload($file, string $type)
    {
        if ($upload = $this->doUploadImage($file, $type)) {
            return $upload['path_upload'];
        }

        return false;
    }
}
