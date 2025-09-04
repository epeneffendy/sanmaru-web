<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Storage;
use File;
use App;

class MigrateAssetCdnToDOSpace extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tools:migrate-assets-to-do-space';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate assets from local to Digital Ocean Space';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $arrayOfPlaces = [
            'About' => ['featured_image'],
            'Blog' => ['featured_image'],
            'Event' => ['image_path'],
            'Gallery' => ['content_url'],
            'Headline' => ['content_url'],
            'Parents' => ['card_identity'],
            'PPDBUser' => ['payment_form', 'birth_certificate', 'photo', 'family_card', 'baptismal_certificate', 'parent_identity_card', 'statement_letter', 'award_photo', 'marriage_certificate', 'report_cards'],
            'Product' => ['image_path'],
            'SchoolLife' => ['featured_image'],
            'Student' => ['image_path'],
            'Testimonial' => ['photo_path'],
            'Testimony' => ['photo_path'],
            'Unit' => ['image_path', 'banner_path', 'keunggulan_path'],
            'VoiceOfSanmar' => ['content_url']
        ];

        foreach ($arrayOfPlaces as $model => $attributes) {
            $model = App::make('App\Models\\'. $model);
            foreach ($attributes as $attribute) {
                $model = $model->orWhere($attribute, '<>', null);
            }
            $datas = $model->get();

            if ($datas) {
                foreach ($datas as $data) {
                    foreach ($attributes as $attribute) {
                        if ($data->$attribute) {
                            $isArray = is_array($data->$attribute);
                            $collectionValues = collect($data->$attribute);
                            
                            foreach ($collectionValues as $key => $val) {
                                if ($value = $this->isLocalImageExists($val)) {
                                    if ($newPath = $this->transfer($value)) {
                                        $collectionValues->put($key, $newPath);
                                        echo 's';
                                    } else {
                                        echo 'x';
                                    }
                                } else {
                                    echo '.';
                                }
                            }

                            if (isset($newPath)) {
                                if ($isArray) {
                                    $data->$attribute = json_encode($collectionValues->all());
                                } else {
                                    $data->$attribute = $collectionValues->first();
                                }

                                if ($data->isDirty()) {
                                    $data->save();
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    private function isLocalImageExists($path)
    {
        $arr = explode('/', $path);
        $filename = $arr[count($arr) - 1];

        if (!Str::startsWith($filename, 'cdndo_')) {
            $path = $arr[count($arr) - 2] .'/'. $filename;

            $driver = 'private';
            $config = config("filesystems.disks.{$driver}");
            if (Storage::disk($driver)->exists((isset($config['prefix']) ? $config['prefix'] . '/' : NULL) . 'images/' . $path)) {
                return $path;
            }
        }

        return false;
    }

    private function transfer($path)
    {
        $arr = explode('/', $path);
        $filename = $arr[count($arr) - 1];

        $privateConfig = config("filesystems.disks.private");
        $digitaloceanConfig = config("filesystems.disks.digital_ocean");

        $path_upload = $arr[count($arr) - 2] . '/'. (isset($digitaloceanConfig['prefix_filename']) ? $digitaloceanConfig['prefix_filename'] : null) . $filename;

        if ($storage = Storage::disk('digital_ocean')->put((isset($digitaloceanConfig['prefix']) ? $digitaloceanConfig['prefix'] .'/' : NULL) . 'images/' . $path_upload, File::get(Storage::disk('private')->path('images/'. $path)), isset($digitaloceanConfig['visibility']) ? $digitaloceanConfig['visibility'] : false)) {
            return $path_upload;
        }

        return false;

    }
}
