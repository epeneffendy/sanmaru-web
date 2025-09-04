<?php

namespace App\Jobs;

use App\Models\ImportJob;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $import;
    protected $importJob;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($import, ImportJob $importJob)
    {
        $this->import = $import;
        $this->importJob = $importJob;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $this->import->import($this->importJob->path, 'public');
            $reports = $this->import->getReport();
            $this->importJob->update([
                'total_success' => count($reports['success']),
                'total_errors' => count($reports['failure']),
                'success' => json_encode($reports['success']),
                'errors' => json_encode($reports['failure']),
                'status' => ImportJob::STATUS_PROCESSING
            ]);
        } catch (\Exception $e) {
            $this->importJob->update([
                'status' => ImportJob::STATUS_FAILED,
                'errors' => json_encode([$e->getMessage()])
            ]);
        }
    }
}
