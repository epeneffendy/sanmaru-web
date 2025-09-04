<?php

namespace App\Http\Controllers\Admin;

use App\Lib\ImportJob;
use App\Models\PPDBUser;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\ProductOrder;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\ProductOrderService;
use App\Imports\CimbUniformPaymentsImport;
use App\Http\Requests\PaymentImportRequest;
use App\Imports\MandiriUniformPaymentsImport;
use App\Imports\MandiriV2UniformPaymentsImport;
use App\Http\Requests\UniformPaymentStoreRequest;

class UniformPaymentController extends Controller
{
    private $page = [
        'parent' => 'shop',
        'child' => 'uniform-payment'
    ];

    public function index()
    {
        return view('administrator.uniform-payment.index', [
            'nav' => $this->page
        ]);
    }

    public function import(PaymentImportRequest $request)
    {
        $params = $request->validated();
        $title = Str::slug("Import Data Cek Pembayaran Seragam " . date('Y-m-d H:i:s'), '_') . '.csv';

        if ($params['payment_method'] == 'cimb') {
            $import = new CimbUniformPaymentsImport;
        } else if ($params ['payment_method'] == 'mandiri') {
            $import = new MandiriUniformPaymentsImport;
        } else if ($params ['payment_method'] == 'mandiri_v2') {
            $import = new MandiriV2UniformPaymentsImport;
        }

        $params = array_merge($params, ['page' => $this->page['child']]);

        $user = Auth::user();
        (new ImportJob)->import($import, $params['file'], $params, $user, $title, true);

        $collects = $import->getCollection();

        $productOrders = ProductOrder::with('user', 'user.ppdb', 'user.ppdb.unit', 'productOrderDetails', 'productOrderDetails.productDetail')->whereHas('user.ppdb', function($query) use ($collects) {
            $registers = $collects->keys()->map(function ($value, $key) {
                return explode('-', $value)[0];
            });
            return $query->whereIn('register_number', $registers);
        })->get()->map(function($item) {
            $item['key'] = $item->user->ppdb->register_number. '-' .$item->grand_total;
            return $item;
        })->whereIn('key', $collects->keys())->keyBy('key');

        return view('administrator.uniform-payment.import', [
            'nav' => $this->page,
            'product_orders' => $productOrders,
            'import_datas' => $collects,
            'params' => Arr::except($params, ['file', 'page']),
            'errors' => $import->getReport()['failure'],
        ]);
    }

    public function store(UniformPaymentStoreRequest $request, ProductOrderService $service)
    {
        $input = $request->validated();
        $service->massSetPaymentVerified($input); 

        return redirect()->route('admin.uniform-payment.index')->with('message', 'Status Pendaftar berhasil diupdate');
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
        
        return view('administrator.uniform-payment.history', [
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

        return view('administrator.uniform-payment.detail-history', [
            'importJob' => $importJob,
            'nav' => $this->page,
            'errors' => $errors,
            'success' => $success,
        ]);
    }

}