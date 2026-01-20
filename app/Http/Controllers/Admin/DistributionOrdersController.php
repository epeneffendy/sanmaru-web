<?php

namespace App\Http\Controllers\Admin;

use App\Exports\DistributionOrderExport;
use App\Http\Requests\DistributionOrdersRequest;
use App\Models\Unit;
use App\Services\DistributionOrdersService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Auth;

class DistributionOrdersController extends Controller
{
    private $page = [
        'parent' => 'shop',
        'child' => 'distribution-order'
    ];

    public function index(DistributionOrdersService $distributionOrdersService)
    {
        $data = $distributionOrdersService->get();
        return view('administrator.distribution-order.list', [
            'nav' => $this->page,
            'units' => Unit::byUserRole()->get(),
            'data' => $data
        ]);
    }

    public function add(Request $request)
    {
        $params = [
            'units' => Unit::byUserRole()->get(),
            'nav' => $this->page
        ];

        return view('administrator/distribution-order/add', $params);
    }

    public function findUniformOrder(Request $request, DistributionOrdersService $distributionOrdersService)
    {

        $data_orders = $distributionOrdersService->findOrderUniform($request->all());
        return view('administrator/distribution-order/partial/_list_order_uniform', ['data' => $data_orders]);
    }

    public function store(DistributionOrdersRequest $request, DistributionOrdersService $distributionOrdersService)
    {
        DB::beginTransaction();
        try {
            $input = $request->validated();
            $distributionOrdersService->create($request->all());
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.distribution-order.index')->with('errors', collect(['Gagal ditambahkan']));
        }
        return redirect()->route('admin.distribution-order.index')->with('message', 'Berhasil ditambahkan');
    }

    public function send(Request $request, DistributionOrdersService $distributionOrdersService)
    {
        DB::beginTransaction();
        try {
            $distributionOrdersService->send($request->id);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.distribution-order.index')->with('errors', collect(['Gagal ditambahkan']));
        }
        return redirect()->route('admin.distribution-order.index')->with('message', 'Berhasil Dikirim');
    }

    public function delete(Request $request, DistributionOrdersService $distributionOrdersService)
    {
        DB::beginTransaction();
        try {
            $distributionOrdersService->delete($request->id);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.distribution-order.index')->with('errors', collect(['Gagal ditambahkan']));
        }
        return redirect()->route('admin.distribution-order.index')->with('message', 'Berhasil Dibatalkan');
    }

    public function confirm(Request $request, DistributionOrdersService $distributionOrdersService)
    {
        DB::beginTransaction();
        try {
            $distributionOrdersService->confirm($request->id);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.distribution-order.index')->with('errors', collect(['Gagal ditambahkan']));
        }
        return redirect()->route('admin.distribution-order.index')->with('message', 'Berhasil Dikonfirmasi');
    }

    public function export(Request $request, DistributionOrdersService $distributionOrdersService)
    {
        $data = $distributionOrdersService->getById($request->id);

        $orders = $distributionOrdersService->getListOrder($request->id);

        $distributionOrderExport = new DistributionOrderExport($orders);
        $dateStart = Carbon::parse(trim(explode('-', $data->date_range)[0]));
        $dateEnd = Carbon::parse(trim(explode('-', $data->date_range)[1]))->endOfDay();

        $title = 'Exports Distribusi Seragam '.$data->unit->name .' '. date('d M Y', strtotime($dateStart)) .' - '. date('d M Y', strtotime($dateEnd)) .' .xlsx';

        return $distributionOrderExport->download($title);
    }

    public function pdf(Request $request, DistributionOrdersService $distributionOrdersService){

        $dateNow = Carbon::now()->format('YmdHis');

        $data = $distributionOrdersService->getById($request->id);

        $orders = $distributionOrdersService->getListOrder($request->id);

        $dateStart = Carbon::parse(trim(explode('-', $data->date_range)[0]));
        $dateEnd = Carbon::parse(trim(explode('-', $data->date_range)[1]))->endOfDay();

        $nama_hari_inggris = date('l'); // Dapatkan nama hari dalam bahasa Inggris
        $hari_indonesia = array(
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        );

        $data = [
            'data'=>$data,
            'orders'=>$orders,
            'day'=>$hari_indonesia[$nama_hari_inggris],
            'date'=>Carbon::now()->format('d-m-Y')
        ];


        $pdf = \PDF::loadView('administrator.distribution-order.pdf', $data);

        return $pdf->download("distribusi-seragam-".$dateNow.".pdf");
    }
}
