<?php

namespace App\Http\Controllers\Admin;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use App\Http\Controllers\Controller;
use App\Models\DeploymentLog;
use Illuminate\Http\Request;
use App\Helpers\Helper;

class DeploymentController extends Controller
{
    private $page = [
        'parent' => 'deploy',
        'child' => ''
    ];

    public function index(Request $request)
    {
        $processReturn = [];

        if ($request->has('wet-run')) {
            $processReturn = $this->process();
        }

        $deployments = DeploymentLog::orderBy('created_at', 'DESC')->get();

        $data = [
            'processReturn' => $processReturn,
            'deployments' => $deployments,
            'nav' => $this->page
        ];

        return view('administrator/deploy/list', $data);
    }

    private function process()
    {
        $process = new Process('sh '. base_path(). '/scripts/deploy.sh');

        try {
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            DeploymentLog::insert([
                'user_id' => auth()->user()->id,
                'is_success' => 1,
                'logs' => $process->getOutput(),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            return [
                'message' => '<pre>'. $process->getOutput() .'</pre>',
                'status' => 'success'
            ];
        } catch (\Exception $error) {
            DeploymentLog::insert([
                'user_id' => auth()->user()->id,
                'is_success' => 0,
                'logs' => $error->getMessage(),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            return [
                'message' => $error->getMessage(),
                'status' => 'failed'
            ];
        }
    }
}