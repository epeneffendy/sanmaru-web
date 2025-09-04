<?php

namespace App\Http\Controllers\Admin;

use App\Lib\ImportJob;
use App\Models\PPDBUser;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Services\PPDBUserService;
use App\Imports\CimbPaymentsImport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Imports\MandiriPaymentsImport;
use App\Http\Requests\PaymentStoreRequest;
use App\Http\Requests\PaymentImportRequest;

class PaymentController extends Controller
{
    private $page = [
        'parent' => 'ppdb',
        'child' => 'payment'
    ];

    public function index()
    {
        return view('administrator.payment.index', [
            'nav' => $this->page
        ]);
    }

    public function import(PaymentImportRequest $request)
    {
        $params = $request->validated();

        $title = Str::slug("Import Data Cek Pembayaran " . date('Y-m-d H:i:s'), '_') . '.csv';

        if ($params['payment_method'] == 'cimb') {
            $import = new CimbPaymentsImport;
        } else if ($params ['payment_method'] == 'mandiri') {
            $import = new MandiriPaymentsImport;
        }

        $params = array_merge($params, ['page' => $this->page['child']]);

        $user = Auth::user();
        (new ImportJob)->import($import, $params['file'], $params, $user, $title, true);

        $collects = $import->getCollection();
        
        $ppdbUsers = PPDBUser::with('unit')->whereIn('register_number', $collects->keys())->get()->keyBy('register_number');

        return view('administrator.payment.import', [
            'nav' => $this->page,
            'ppdb_users' => $ppdbUsers,
            'import_datas' => $collects,
            'params' => Arr::except($params, ['file', 'page']),
            'errors' => $import->getReport()['failure'],
        ]);
    }

    public function store(PaymentStoreRequest $request, PPDBUserService $ppdbUserService)
    {
        $input = $request->validated();
        $ppdbUserService->massSetPaymentVerified($input); 

        return redirect()->route('admin.payment.index')->with('message', 'Status Pendaftar berhasil diupdate');
    }

    public function history()
    {
        $arrParams = [
            'cimb' => ['page' => $this->page['child'], 'payment_method' => 'cimb'],
            'mandiri' => ['page' => $this->page['child'], 'payment_method' => 'mandiri'],
        ];

        if (request()->payment_method) {
            $arrParams = [$arrParams[request()->payment_method]];
        }

        $importJobs = (new ImportJob)->getAllByParams($arrParams, true);

        if (request()->username) {
            $importJobs = $importJobs->whereHas('user', function ($query) {
                $query->where('username', 'like', '%'.request()->username.'%');
            });
        }

        $importJobs = $importJobs->paginate();
        
        return view('administrator.payment.history', [
            'importJobs' => $importJobs,
            'nav' => $this->page,
            'params' => request()->except(['page']),
        ]);
    }

    public function detailHistory($importJobId)
    {
        $importJob = \App\Models\ImportJob::where('id', $importJobId)->firstOrFail();

        $errors = [];
        if ($importJob->errors) {
            $errors = json_decode($importJob->errors, true);
        }

        $success = [];
        if ($importJob->success) {
            $success = json_decode($importJob->success, true);
        }

        return view('administrator.payment.detail-history', [
            'importJob' => $importJob,
            'nav' => $this->page,
            'errors' => $errors,
            'success' => $success,
        ]);
    }

}