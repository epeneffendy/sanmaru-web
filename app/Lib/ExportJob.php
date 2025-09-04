<?php
namespace App\Lib;

use Illuminate\Support\Facades\Log;
use App\Models\ExportJob as Export;
use App\Jobs\UpdateExportJob;

class ExportJob
{
    public function get(array $params)
    {
        return Export::where('params', $this->key($params))
            ->where('user_id', $user['id'])
            ->first();
    }

    private function key($params)
    {
        ksort($params);
        $key = '';
        foreach ($params as $key=>$param) {
            $key .= $key.":". json_encode($param);
        }
        return md5('2'.$key);
    }

    public function export($export, $params, $user, $title)
    {
        if ($first = @$export->collection()->first()['updated_at']) {
            $params['updated_at'] = $first;
        }

        $exportJob = Export::create([
            'params' => $this->key($params),
            'user_id' => $user['id'],
        ]);

        $update = ['show' => 1];
        //if ($existing = Export::where([
        //    'params' => $this->key($params),
        //    'status' => 'finished',
        //    ['path', '<>', null]
        //])->first()) {
        //    $update['status'] = 'finished';
        //    $update['path'] = $existing->path;
        //}

        $exportJob->update($update);
        $exportJob->refresh();

        if (!$exportJob->path) {
            $exportJob->update(['status' => 'processing']);
            $export->queue('exports/'. $title, 'private')->allOnQueue('exports')->chain([
                new UpdateExportJob($exportJob, $title)
            ])->onQueue('exports');
        }
    }

    public function message($params, $user)
    {
        if ($exportOpen = Export::where([
            'user_id' => $user['id'],
            'show' => 1
        ])->first()) {
            switch ($exportOpen->status) {
                case 'not_started':
                    return 'Export belum berjalan';
                case 'processing':
                    return 'proses export sedang berjalan ..... <a href="javascript:location.reload();">klik disini untuk reload</a>';
                case 'finished':
                    $exportOpen->update(['show' => false]);
                    return 'proses export selesai <a href="'. route('show_export', ['file' => $exportOpen->path]) .'" download target="_blank">download disini</a>';
            }

        }

        return null;
    }
}
