<?php

namespace App\Http\Controllers\Admin;

use App\Models\ComplaintOrders;
use App\Models\ComplaintPeriode;
use App\Models\ProductOrder;
use App\Models\User;
use App\Services\ComplaintOrderService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ComplaintOrdersController extends Controller
{
    private $page = [
        'parent' => 'shop',
        'child' => 'complaint'
    ];

    public function index(ComplaintOrderService $complaintOrderService)
    {
        $data = $complaintOrderService->get();
        $periodeComplaint = ComplaintPeriode::get();

        return view('administrator.complaint-order.list', [
            'nav' => $this->page,
            'datas' => $data,
            'periode' => $periodeComplaint
        ]);
    }

    public function show(Request $request, ComplaintOrderService $complaintOrderService)
    {
        $data = $complaintOrderService->getById($request->id);

        $productOrder = ProductOrder::whereId($data->productOrderDetail->product_order_id)->first();

        $userUpdate = User::whereId($data->updated_by)->first();

        return view('administrator.complaint-order.show', [
            'nav' => $this->page,
            'data' => $data,
            'productOrder' => $productOrder,
            'userUpdate' => $userUpdate
        ]);
    }

    public function changeStatus(Request $request, ComplaintOrderService $complaintOrderService)
    {
        DB::beginTransaction();
        try {
            if ($request->flag == 'proccess') {
                $update = $complaintOrderService->changeStatus($request->id, ComplaintOrders::STATUS_PROCESS);
            }

            if ($request->flag == 'rejected') {
                $desc = [
                  'reason' => $request->reason
                ];
                $update = $complaintOrderService->changeStatus($request->id, ComplaintOrders::STATUS_REJECTED, $desc);
            }

            if ($request->flag == 'done') {
                $update = $complaintOrderService->changeStatus($request->id, ComplaintOrders::STATUS_DONE);
            }

            if ($request->flag == 'pickup') {
                $desc = [
                    'date' => $request->date,
                    'location' => $request->location,
                ];
                $update = $complaintOrderService->changeStatus($request->id, ComplaintOrders::STATUS_PICKUP, $desc);
            }

            if ($update['success'] == true) {
                DB::commit();

            } else {
                DB::rollBack();
            }
            return [
                'success' => $update['success'],
                'message' => $update['message']
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Proses Gagal!'
            ];
        }
    }

    public function settingPeriod(Request $request, ComplaintOrderService $complaintOrderService)
    {
        DB::beginTransaction();
        try {
            $setting = $complaintOrderService->settingPeriod($request->all());
            DB::commit();
        }catch (\Exception $e){
            dd($e);
            DB::rollBack();
        }
    }
}
