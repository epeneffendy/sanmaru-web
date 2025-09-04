<?php
namespace App\Traits;

use Illuminate\Support\Str;
use Storage;
use File;

trait ImageHandler
{
    private function doUploadImage($file, $type)
    {
        $driver = config('filesystems.default');
        $user = auth()->user();
        $config = config("filesystems.disks.{$driver}");

        $path = $type . '/';
        $filename = bin2hex(@$user['id'] .
            $file->getFilename()) .
            '.' .
            $file->extension();
        $path_upload = $path . (isset($config['prefix_filename']) ? $config['prefix_filename'] : null) . $filename;

        if ($storage = Storage::disk($driver)->put((isset($config['prefix']) ? $config['prefix'] .'/' : NULL) . 'images/' . $path_upload, File::get($file), isset($config['visibility']) ? $config['visibility'] : false)) {
            return [
                'path_upload' => $path_upload,
                'path_url' => Storage::disk($driver)->url('images/'. $path_upload),
                'path' => Storage::disk($driver)->url('images/'. $path_upload),
                'filename' => $filename,
            ];
        }

        return false;
    }

    private function imageExists($path)
    {
        $driver = config('filesystems.default');
        $config = config("filesystems.disks.{$driver}");
        return Storage::disk($driver)->exists((isset($config['prefix']) ? $config['prefix'] . '/' : NULL) . 'images/' . $path);
    }

    private function deleteImage($path)
    {
        $driver = config('filesystems.default');
        $config = config("filesystems.disks.{$driver}");
        return Storage::disk($driver)->delete((isset($config['prefix']) ? $config['prefix'] . '/' : NULL) . 'images/' . $path);
    }

    private function getImageUrl($path)
    {
        $driver = config('filesystems.default');

        return Storage::disk($driver)->url('images/'. $path);
    }

    private function moveImage($from, $to)
    {
        $driver = config('filesystems.default');
        $config = config("filesystems.disks.{$driver}");
        $prefix = (isset($config['prefix']) ? $config['prefix'] . '/' : NULL) . 'images/';
        return Storage::disk($driver)->move( $prefix . $from, $prefix . $to );
    }

    private function getImageFile($path)
    {
        $driver = config('filesystems.default');
        $config = config("filesystems.disks.{$driver}");
        $driver = config('filesystems.default');
        return Storage::disk($driver)->get((isset($config['prefix']) ? $config['prefix'] . '/' : NULL) .'images/'. $path);
    }
}
