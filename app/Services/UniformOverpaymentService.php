<?php

namespace App\Services;

use App\Models\PaymentRefund;
use App\Models\ProductOrder;
use App\Models\UniformPayment;
use App\Models\Unit;
use App\Models\PPDBUser;

class UniformOverpaymentService 
{
	public function generateIndexData($nav)
	{
		$uniformPayments = UniformPayment::with([
								'productOrder', 
								'productOrder.user', 
								'productOrder.user.ppdb', 
								'productOrder.user.ppdb.unit',
								'productOrder.overpayment',
								'productOrder.productOrderDetails',
								'productOrder.productOrderDetails.productDetail',
							])
							->get()->filter(function ($uniformPayment) {
								return $uniformPayment->overpayment 
									&& $uniformPayment->productOrder->status === ProductOrder::STATUS_CONFIRMED;
							});

		return [
			'datas' => $uniformPayments,
			'nav' => $nav
		];
	}

	public function generateAddingData($nav)
	{
		$uniformPayment = new UniformPayment();
		return [
			'data' => $uniformPayment,
			'units' => Unit::all(),
			'nav' => $nav
		];
	}

	public function generateEditableData($id, $nav)
	{
		$uniformPayment = UniformPayment::whereHas('productOrder', function ($query) {
									return $query->where('status', ProductOrder::STATUS_CONFIRMED);
								})
								->where('id', $id)->firstOrFail();

		return [
			'data' => $uniformPayment,
			'status' => 'edit',
			'nav' => $nav
		];
	}

	public function generateShowingData($id, $nav)
	{
		$uniformPayment = UniformPayment::whereHas('productOrder', function ($query) {
								return $query->where('status', ProductOrder::STATUS_CONFIRMED);
							})
							->where('id', $id)->firstOrFail();

		return [
			'data' => $uniformPayment,
			'nav' => $nav
		];
	}

	public function update($id, $params)
	{
		$uniformPayment = UniformPayment::whereHas('productOrder', function ($query) {
									return $query->where('status', ProductOrder::STATUS_CONFIRMED);
								})->where('id', $id)->firstOrFail();
		$params = $this->params($params);
		$uniformPayment->fill($params);
		$uniformPayment->save();

		$paymentRefundService = new PaymentRefundService();
		$paymentRefundService->updateOrCreate($params);

		return $uniformPayment;
	}

	public function create($params)
	{
		$params = $this->params($params);
		$uniformPayment = UniformPayment::create($params);

		$paymentRefundService = new PaymentRefundService();
		$paymentRefundService->updateOrCreate($params);

		return $uniformPayment;
	}

	public function params($params)
	{
		$productOrder = ProductOrder::whereHas('user.ppdb', function ($query) use ($params) {
								return $query->where('register_number', $params['register_number']);
							})
							->where('invoice_no', $params['invoice_no'])
							->firstOrFail();

		$params['product_order_id'] = $productOrder->id;
		$params['user_id'] = $productOrder->user_id;
		$params['refund_id'] = $productOrder->id;
		$params['refund_type'] = PaymentRefund::TYPE_UNIFORM;
		$params['nominal_price'] = $params['payment_amount'];
		$params['updated_by_id'] = request()->user()->id;
		$params['cause'] = PaymentRefund::CAUSE_OVERPAYMENT;

		return $params;
	}

	public function getStudentData($params) 
	{
		if (isset($params['unit']) && $params['unit'] 
			&& isset($params['register_number']) && $params['register_number']) {

			$ppdbUser = PPDBUser::with(['ordersConfirmed' => function ($query) {
										return $query->doesntHave('uniformPayment');
									}])
									->where('unit_id', $params['unit'])
									->where('register_number', $params['register_number'])
									->first();

			if (!is_null($ppdbUser)) {
				$product_orders = collect();
				$ppdbUser->ordersConfirmed->each(function ($order) use ($product_orders) {
					$product_orders->push([
						'invoice_no' => $order->invoice_no,
						'grand_total' => $order->grand_total
					]);
				});
				
				return [
					'register_number' => $ppdbUser->register_number,
					'name' => $ppdbUser->name,
					'product_order' => $product_orders
				];
			}
		}

		return null;
	}
}