<?php
namespace App\Jobs;

use App\Models\ExportJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class UpdateExportJob implements ShouldQueue
{
    use Queueable, SerializesModels;
    
    public $exportJob;
    public $path;
    
    public function __construct(ExportJob $exportJob, $path)
    {
        $this->exportJob = $exportJob;
        $this->path = $path;
    }

    public function handle()
    {
        ExportJob::where('params', $this->exportJob->params)->update([
            'status' => 'finished',
            'path' => $this->path,
        ]);
        chmod(base_path() . '/storage/app/private/exports/'. $this->path, 775);
    }
}
