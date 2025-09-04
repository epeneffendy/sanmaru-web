<?php

namespace App\Jobs;

use App\Models\ImportJob;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FinishImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $importJob;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ImportJob $importJob)
    {
        $this->importJob = $importJob;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // get tmp file
        $file = Storage::disk('public')->get($this->importJob->path);
        
        // upload to default disk
        Storage::put($this->importJob->path, $file);
        
        // delete tmp file
        Storage::disk('public')->delete($this->importJob->path);

        // update status
        if ($this->importJob->status !== ImportJob::STATUS_FAILED) {
            $this->importJob->update([
                'status' => ImportJob::STATUS_FINISHED
            ]);
        }
    }
}
