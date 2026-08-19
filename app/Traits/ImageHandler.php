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
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension());
        $filename = bin2hex(@$user['id'] .
            $file->getFilename()) .
            '.' .
            $extension;
        $path_upload = $path . (isset($config['prefix_filename']) ? $config['prefix_filename'] : null) . $filename;

        if ($storage = Storage::disk($driver)->put((isset($config['prefix']) ? $config['prefix'] .'/' : NULL) . 'images/' . $path_upload, File::get($file), isset($config['visibility']) ? $config['visibility'] : false)) {
            $url = $this->getImageUrl($path_upload);
            return [
                'path_upload' => $path_upload,
                'path_url' => $url,
                'path' => $url,
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

    private function getImageUrl($path, $expirationMinutes = 60)
    {
        if (empty($path)) {
            return null;
        }

        if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
            return $path;
        }

        $driver = config('filesystems.default');

        try {
            if (in_array($driver, ['s3', 'digital_ocean'])) {
                return Storage::disk($driver)->temporaryUrl(
                    'images/' . $path,
                    now()->addMinutes($expirationMinutes)
                );
            }
        } catch (\Throwable $e) {
            // Fallback to standard url if temporaryUrl fails or is unsupported
        }

        return Storage::disk($driver)->url('images/' . $path);
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
