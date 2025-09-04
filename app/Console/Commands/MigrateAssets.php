<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Storage;
use File;

class MigrateAssets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tools:migrate-assets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate Assets from private to digital_ocean';

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
        $prefix = 'images';
        $files = Storage::disk('private')->allFiles($prefix);
        $digitaloceanConfig = config("filesystems.disks.digital_ocean");

        foreach ($files as $file) {
            $this->transfer($file);
        }
    }

    private function transfer($file)
    {
        if (Storage::disk('digital_ocean')->put((isset($digitaloceanConfig['prefix']) ? $digitaloceanConfig['prefix'] .'/' : NULL) . $file, File::get(Storage::disk('private')->path($file)), isset($digitaloceanConfig['visibility']) ? $digitaloceanConfig['visibility'] : false)) {
            echo 's';
        } else { 
            echo '.';
        }
    }
}
