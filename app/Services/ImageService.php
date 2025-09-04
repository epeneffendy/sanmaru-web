<?php
namespace App\Services;

use App\Traits\ImageHandler;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use DomDocument;

class ImageService
{
    use ImageHandler;

    const PATH_STUDENT = 'students';
    const PATH_PRIVATE = '/storage/app/private/images/';

    const PATH_TEMP = 'temp';

    public function upload($path, $pivot, $file)
    {
        if ($upload = $this->doUploadImage($file, $path)) {
            return $upload['path_upload'];
        }

        return null;
    }

    public function uploadTemp($request, string $type)
    {
        if ($request->hasFile($type) && $upload = $this->doUploadImage($request->file($type), $this::PATH_TEMP)) {
            return $upload;
        }

        return null;
    }


    public function filterUploadHTML($html, string $type)
    {
        $domHtml = new DOMDocument();
        libxml_use_internal_errors(true);
        $domHtml->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES'));
        $imgTags = $domHtml->getElementsByTagName('img');

        foreach($imgTags as $img) {
            $src = $img->getAttribute('src');
            if (Str::contains($src, $this::PATH_TEMP)) {
                $file           = Str::after($src, $this::PATH_TEMP . '/');
                $source         = $this::PATH_TEMP . '/' . $file;
                $destination    = $type . '/' . $file ;

                if ($this->imageExists($source) && $this->moveImage($source, $destination)) {
                    $html = str_replace($src, $destination, $html);
                }
            }
        }
        libxml_clear_errors();
        return $html;
    }

    public function filterUpdateHTML($current, $new)
    {
        $domHtml = new DomDocument();
        libxml_use_internal_errors(true);
        $domHtml->loadHtml($current);
        $imgTags = $domHtml->getElementsByTagName('img');

        foreach($imgTags as $img) {
            $src = $img->getAttribute('src');
            if (!Str::contains($new, $src)) {
                $this->deleteImage($src);
            }
        }
        libxml_clear_errors();
    }

    public function filterDeleteHTML($html, string $type)
    {
        $domHtml = new DomDocument();
        libxml_use_internal_errors(true);
        $domHtml->loadHtml($html);
        $imgTags = $domHtml->getElementsByTagName('img');

        foreach($imgTags as $img) {
            $src = $img->getAttribute('src');
            if (Str::startsWith($src, 'content_image')) {
                $this->deleteImage($src);
            }
        }

        libxml_clear_errors();
    }

    public function getFile($path)
    {
        return $this->getImageFile($path);
    }

    public function getUrl($path)
    {
        return $this->getImageUrl($path);
    }
}
