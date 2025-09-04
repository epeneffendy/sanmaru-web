<?php 

namespace App\Lib;

use App\Jobs\FinishImportJob;
use App\Jobs\ProcessImportJob;
use App\Models\ImportJob as Import;

class ImportJob 
{
    public function getAllByParams(array $arrParams, $queryOnly = false)
    {
        $arrKey = [];
        foreach ($arrParams as $params) {
            $arrKey[] = $this->key($params);
        }

        $importJobs = Import::with('user')->whereIn('params', $arrKey)->latest();

        if ($queryOnly) {
            return $importJobs;
        }

        return $importJobs->get();
    }

    private function key($params)
    {
        foreach ($params as $key => $param) {
            if (is_file($param)) {
                unset($params[$key]);
            }
        }
        
        ksort($params);
        $key = json_encode($params);
        return md5($key);
    }

    public function getProcessingJob($key, $user)
    {
        return Import::where('params', $key)
            ->where('user_id', $user['id'])
            ->whereNotIn('status', ['finished', 'failed'])
            ->first();
    }

    public function import($import, $file, $params, $user, $filename, $cekPembayaran = false)
    {
        $key = $this->key($params);

        if ($importJob = $this->getProcessingJob($key, $user)) {
            return;
        }

        $prefix = 'imports/' . $params['page'] . '/';
        $path = $prefix . $filename;

        // save file temporary for processing jobs
        $file->storeAs('', $path, 'public');

        $importJob = Import::create([
            'params' => $key,
            'user_id' => $user['id'],
            'path' => $path,
        ]);

        if ($cekPembayaran) { // get collection
            ProcessImportJob::dispatchNow($import, $importJob);
            FinishImportJob::dispatchNow($importJob);
        } else {
            ProcessImportJob::withChain([
                new FinishImportJob($importJob),
            ])->dispatch($import, $importJob);
        }
    }

    public function message($params, $user)
    {
        $key = $this->key($params);
        $importJob = Import::where('params', $key)
                        ->where('user_id', $user['id'])
                        ->first();

        $message = null;
        if ($importJob) {
            switch ($importJob->status) {
                case 'not_started':
                    $message = 'import belum berjalan';
                    break;
                case 'processing':
                    $message = 'proses import sedang berjalan ....';
                    break;
                case 'finished':
                    $message = 'proses import selesai <a href="'.route('show_import', ['file' => $importJob->path]).'" download target="_blank">download disini</a>';
                    break;
                    
            }
        }

        return $message;
    }
}