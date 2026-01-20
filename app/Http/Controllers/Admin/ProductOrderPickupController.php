<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Carbon\Carbon;
use App\Models\Unit;
use App\Lib\ExportJob;
use App\Models\Period;
use App\Models\Classes;
use Illuminate\Support\Str;
use App\Models\ProductOrder;
use App\Traits\ImageHandler;
use Illuminate\Http\Request;
use App\Enums\ProductTypeEnum;
use App\Services\PeriodService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\ProductOrderService;
use App\Exports\ProductOrderPickupExport;
use App\Services\ProductOrderPickupService;
use App\Http\Requests\ProductOrderPickupScheduleRequest;

class ProductOrderPickupController extends Controller
{
	use ImageHandler;

    private $page = [
        "parent" => "shop",
        "child" => "product-order-pickup"
    ];

    public function index(Request $request, ProductOrderPickupService $productOrderPickupService)
    {

        $related = [
            'user',
            'user.student',
            'user.student.class',
            'user.student.class.unit',
            'user.ppdb',
            'user.ppdb.unit',
            'productOrderDetails',
            'productOrderDetails.productDetail',
        ];
        $params = $request->all();

        $productOrders = collect();
        foreach (ProductTypeEnum::getValues() as $type) {
            $params['type'] = $type;
            $productOrders->put($type, $productOrderPickupService->filter($params, 10, $related));
        }

        $data = [
            'nav' => $this->page,
            'units' => Unit::byUserRole()->get(),
            'years' => $productOrderPickupService->getAvailableYears(),
            'product_orders' => $productOrders,
            'params' => $request->only(['student_name', 'payment_mail_confirmation', 'pickup_status', 'unit', 'year' ,'page','date_range','type_user' ])
        ];

    	return view('administrator.product-order-pickup.list', $data);
    }

    public function show($id, ProductOrderPickupService $productOrderPickupService)
    {
    	$data = $productOrderPickupService->generateShowingData($id, $this->page);
    	return view('administrator.product-order-pickup.show', $data);
    }

    public function uploadPickupImage($id, Request $request, ProductOrderPickupService $productOrderPickupService)
    {
    	$validated = $request->validate([
	        'pickup_image' => 'nullable|mimes:jpeg,jpg,png',
	    ]);

    	$data = $productOrderPickupService->uploadPickupImage($id, $validated);
    	return redirect()->back()->withMessage('berhasil diupdate');
    }

    public function pickup(Request $request, ProductOrderPickupService $productOrderPickupService)
    {
        $params = $request->input();
    	try {
			$productOrder = ProductOrder::paymentConfirmed()
								->where('id', $params['id'])->first();

			$productOrder->fill([
				'pickup_status' => 'pickup',
				'pickup_date' => Carbon::now()->toDateTimeString(),
			]);

			$productOrder->save();
            if ($request->response == 'page') {
                return redirect()->back();
            }
			return response()->json([
				'message' => 'berhasil disimpan',
				'data' => $productOrder
			], 202);

		} catch (\Exception $e) {
			if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
				return response()->json(null, 404);
			} else {
				Log::error($e);
				return response()->json(null, 400);
			}
		}
    }

	public function sendConfirmation($id, ProductOrderService $service)
	{
    	$data = $service->sendPaymentConfirmedEmail($id);
    	return redirect()->back()->withMessage('email sedang proses untuk dikirim');
	}

    public function sendPickupConfirmation(Request $request, ProductOrderPickupService $service)
    {
        $input = $request->input();
        if (!isset($input['id'])) {
            return response()->json(null, 400);
        }
        try {
            $data = $service->sendPickupConfirmedEmail($input['id']);
            return response()->json([
                'message' => 'email sedang proses untuk dikirim'
            ], 200);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(null, 400);
        }
    }

    public function export(Request $request)
    {
        $productOrderPickupExport = new ProductOrderPickupExport($request->all());
        $title = Str::slug("Exports Data Pengambilan Seragam " . date('Y-m-d H:i:s'), '_') . ".xlsx";

        // Queuing export on export job
        // (new ExportJob())->export($productOrderPickupExport, array_merge($request->only('unit'), ['page' => 'product-order-pickup']), Auth::user(), $title);
        // return back()->withSuccess('Export started!');

        // Queuing export using laravel excel
        // $productOrderPickupExport->queue('exports/'. $title, 'private')->allOnQueue('exports');

        // Vanilla export
        return $productOrderPickupExport->download($title);
    }

    public function cancelPickup($id, ProductOrderPickupService $productOrderPickupService)
    {
        $data = $productOrderPickupService->cancelPickup($id);
        return redirect(route('admin.product-order-pickup.index'))->withMessage('berhasil dibatalkan');
    }

    public function createSchedule(Request $request, ProductOrderPickupService $productOrderPickupService)
    {
        if ($request->has('product_order')) {
            $productOrder = ProductOrder::findOrFail($request->input('product_order'));
            $data = [
                'nav' => $this->page,
                'productOrder' => $productOrder,
            ];
        } else {
            $data = [
                'nav' => $this->page,
                'units' => Unit::all(['id', 'name']),
                'periods' => Period::with('unit')->orderBy('unit_id')->get(),
                'years' => $productOrderPickupService->getAvailableYears(),
            ];
        }
        return view('administrator.product-order-pickup.schedule', $data);
    }

    public function storeSchedule(ProductOrderPickupScheduleRequest $request, ProductOrderPickupService $productOrderPickupService)
    {
        $totalScheduled = $productOrderPickupService->schedule($request->all());

        return redirect()->route('admin.product-order-pickup.index')->withMessage('Pengambilan untuk ' . $totalScheduled . ' pesanan berhasil dijadwalkan');
    }

    public function resetSchedule($id, Request $request, ProductOrderPickupService $productOrderPickupService)
    {
        $productOrderPickupService->resetSchedule($id, $request->all());
        return redirect()->route('admin.product-order-pickup.index')->withMessage('Jadwal berhasil di reset');
    }

    public function fetchPeriod(Request $request, PeriodService $periodService)
    {
        $periods = $periodService->filter($request->all(), null, ['unit']);
        return $periods;
    }

    public function showQrResult($id, ProductOrderPickupService $productOrderPickupService)
    {
        $productOrder = ProductOrder::where('id', $id)->whereNotNull('pickup_date_schedule')->first();

        $data = [
            'productOrder' => $productOrder,
            'nav' => $this->page,
        ];

        return view('administrator.product-order-pickup.qr-result', $data);
    }
}
