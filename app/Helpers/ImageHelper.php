<?php
namespace App\Helpers;

use App\Services\ImageService;
use App\Traits\ImageHandler;

class ImageHelper
{
    use ImageHandler;

    public static function getImage($filename)
    {
        $path = base_path() . ImageService::PATH_PRIVATE . $filename;
        // $type = (strpos($filename, 'jpg') !== false) ? "image/jpeg" : (strpos($filename, 'jpeg') !== false) ? "image/jpeg" : "image/png";
        $type = '';
        header('Content-Type:' . $type);
        header('Content-Length: ' . filesize($path));
        readfile($path);
    }

    public static function imageUrl($path)
    {
        return (new self)->getImageUrl($path);
    }

    public static function unit_about($filename)
    {
        if (is_file("/frontend-ppdb-online/img/profile/{$filename}-tentang-sekolah.jpg")) {
            return "/frontend-ppdb-online/img/profile/{$filename}-tentang-sekolah.jpg";
        }
        return '/frontend-ppdb-online/img/profile/SMP-SURABAYA-tentang-sekolah.jpg';
    }

    public static function unit_advantage($filename)
    {
        if (is_file("/frontend-ppdb-online/img/profile/{$filename}-keunggulan.jpg")) {
            return "/frontend-ppdb-online/img/profile/{$filename}-keunggulan.jpg";
        }
        return '/frontend-ppdb-online/img/profile/SMP-SURABAYA-keunggulan.jpg';
    }
}
