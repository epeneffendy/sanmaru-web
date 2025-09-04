<?php 

namespace App\Services;

use App\Helpers\PriceHelper;
use App\Models\Finance;
use App\Models\PaymentRefund;
use App\Models\PPDBUser;
use App\Traits\ImageHandler;
use Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentRefundService 
{
	use ImageHandler;

	public function generateShowData($id, $nav) {

		$paymentRefund = PaymentRefund::where('id', $id)
										->with([
											'user',
											'user.ppdb',
											'user.ppdb.unit',
											'productOrders',
											'productOrders.uniformPayment'
										])
										->firstOrFail();

		return [
			'nav' => $nav,
			'data' => $paymentRefund
		];
	}

	public function generateAddingData($ppdbUserId, $nav) {

		$ppdbUser = PPDBUser::withTrashed()
								->byUserRole()
								->with([
									'unit', 
									'user',  
									'ordersConfirmed',
									'paymentRefundDevelopment'
								])
								->where('id', $ppdbUserId)
								->firstOrFail();

		$paymentRefund = new PaymentRefund();

		if (! is_null($ppdbUser->paymentRefundDevelopment)) {
			$development = null;
		} else {
			$development = $this->getFinanceData($ppdbUser, 'development');
			if (! is_null($development) && ! is_empty($development)) {
				$development->nominal_default = PriceHelper::development($ppdbUser, false, $ppdbUser->school_year);
			}
		}

		return [
			'nav' => $nav,
			'ppdbUser' => $ppdbUser,
			'development' => $development,
			'units' => \App\Models\Unit::all(),
			'paymentRefund' => $paymentRefund,
			'causes' => $paymentRefund->listCause()
		];
	}

	private function getFinanceData($model, $pattern, $year=null) {
		$finances = Cache::get('finance-all');

		if (is_null($finances)) {
			$finances = Finance::select('nominal_default', 'code')->get()->keyBy('code')->toArray();
		}

		$keys = collect();
        $year = $year?: date('Y');
        $unit = null;
        $user = null;
        $data = null;

        if ($model instanceof PPDBUser) {
            $unit = $model->unit;
            $user = $model->user_Id;
        }

        if ($model instanceof Student) {
            $unit = $model->class->unit;
            $user = $model->user_id;
        }

        if ($model instanceof Unit) {
            $unit = $model;
        }

        if ($unit) {
            if ($user) {
                $keys->push($pattern .'.'. $unit->name .'.'. $user .'.'. $year);
                $keys->push($pattern .'.'. $unit->name .'.'. $user);
            }

            $keys->push($pattern .'.'. $unit->name .'.'. $year);
            $keys->push($pattern .'.'. $unit->name);
        }

        if ($user) {
            $keys->push($pattern.'.'. $user .'.'. $year);
            $keys->push($pattern.'.'. $user);
        }

        $keys->push($pattern.'.'.$year);
        $keys->push($pattern);

        if (count($finances)) {
            foreach ($keys as $key) {
                if (isset($finances[$key])) {
                    $data = Finance::where('code', $key)->first();     
                    break;
                }
            }
        }

		return $data;
	}

	public function confirmRefund($id) {
		$paymentRefund = PaymentRefund::where('id', $id)->firstOrFail();

		DB::beginTransaction();

		try {
			if ($paymentRefund->refund instanceof \App\Models\ProductOrder 
				&& $paymentRefund->cause === PaymentRefund::CAUSE_REPAYMENT) {
				$productOrder = $paymentRefund->refund;
				$productOrder->productOrderDetails->each(function($productOrderDetail) {
            		$productOrderDetail->delete();
        		});

        		$productOrder->delete();
			}

			$paymentRefund->fill([
				'status' => PaymentRefund::STATUS_CONFIRMED,
				'updated_by_id' => request()->user()->id
	 		]);

	 		$paymentRefund->save();
	 		DB::commit();
	 		return true;
	 	} catch (\Exception $e) {
	 		DB::rollback();
	 		Log::error($e);
	 	}

 		return false;
	}

	private function params($params)
	{
		if (isset($params['refund_image']) && request()->hasFile('refund_image')) {
			if (is_array($params['refund_image'])) {
				foreach($params['refund_image'] as $key => $refund_image) {
					if ($image = $this->uploadImage($refund_image)) {
						$params['refund_image'][$key] = $image;
					}
				}
			} else {
				if ($image = $this->uploadImage($params['refund_image'])) {
					$params['refund_image'] = $image;
				} 
			}
		}


		if (isset($params['refund_code']) && $params['refund_code']) {
			$params['refund_type'] = ($params['refund_code'] == 'uniform') ? 'product-order' : 'finance';
		}

		$params['updated_by_id'] = request()->user()->id;

		return $params;
	}

	private function uploadImage($file) {
		$type = 'refund_image';
		if ($upload = $this->doUploadImage($file, $type)) {
			return $upload['path_upload'];
		}

		return false;
	}

	public function create($params)
	{
		$params = $this->params($params);
		$paymentRefund = PaymentRefund::create($params);
		return $paymentRefund;
	}

	public function update($id, $params)
	{
		$paymentRefund = PaymentRefund::where('id', $id)->firstOrFail();
		$params = $this->params($params);
		$paymentRefund->fill($params);
		return $paymentRefund->save();

	}

	public function updateOrCreate($params)
	{
		$paymentRefund = PaymentRefund::where('refund_id', $params['refund_id'])
							->where('refund_type', $params['refund_type'])
							->where('cause', $params['cause'])
							->first();

		if (is_null($paymentRefund)) {
			$this->create($params);
		} else {
			$this->update($paymentRefund->id, $params);
		}
	}
}