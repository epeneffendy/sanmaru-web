<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRefundStoreRequest;
use App\Models\ProductOrder;
use App\Services\PaymentRefundService;

class PaymentRefundController extends Controller
{
	private $page = [
		'parent' => 'ppdb',
		'child' => 'payment-refund'
	];

	public function orderDetail($productOrderId)
	{
		$productOrder = ProductOrder::where('id', $productOrderId)
			->with('productOrderDetails', 'productOrderDetails.product', 'productOrderDetails.productDetail')
			->first();

		if (is_null($productOrder)) {
			return response()->json(['error' => 'data tidak ditemukan'], 404);
		}
			
		return response()
			->json([
				'data' => [
					'invoice_no' => $productOrder->invoice_no,
					'status' => $productOrder->status,
					'grand_total' => $productOrder->grand_total,
					'created_at' => $productOrder->created_at->format('d-m-Y H:i:s'),
					'updated_at' => $productOrder->updated_at->format('d-m-Y H:i:s')
				],
				'html' => view('administrator.payment-refund._order-detail', ['productOrder' => $productOrder])->render()
			]);
	}

    public function confirmRefund($id, PaymentRefundService $paymentRefundService) {
    	$paymentRefundService->confirmRefund($id);
    	return redirect()->back()->withMessage('berhasil dikonfirmasi');
    }
}
