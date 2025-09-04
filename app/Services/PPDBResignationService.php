<?php

namespace App\Services;

use App\Models\Finance;
use App\Models\PaymentRefund;
use App\Models\Parents;
use App\Models\PPDBUser;
use App\Models\PPDBResignation;
use App\Models\Unit;
use App\Traits\ImageHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PPDBResignationService 
{
	use ImageHandler;

	public function generateIndexData($request, $nav)
	{
		$data = PPDBResignation::with([
						'unit', 
						'ppdbUser',
						'ppdbUser.user', 
						'ppdbUser.paymentRefundDevelopment',
						'ppdbUser.paymentRefundUniform',
					])->whereHas('ppdbUser', function ($query) {
						return $query->byUserRole();
					});

        if ($request->input('name')) {
        	$name = $request->input('name');
            $data = $data->whereHas('ppdbUser', function ($query) use ($name) {
            	return $query->where('name', 'like', '%'.$name.'%');
            });
        }
        if ($request->input('unit')) {
            $data = $data->where('unit_id', $request->input('unit'));
        }
        if ($request->input('start_date') || $request->input('end_date')) {

        	$data = $data->whereHas('ppdbUser.paymentRefundDevelopment', function ($query) use ($request) {
							if ($request->input('start_date')) {
								$start_date = Carbon::createFromFormat('Y-m-d', $request->input('start_date'))
				        					->startOfDay()
				        					->toDateTimeString();

        						$query = $query->where('updated_at', '>=' , $start_date);
        					}
        					if ($request->input('end_date')) {
        						$end_date = Carbon::createFromFormat('Y-m-d', $request->input('end_date'))
				        					->endOfDay()
				        					->toDateTimeString();

					        	$query = $query->where('updated_at', '<=' , $end_date);
					        }
					        return $query;
						})
        				->orWhereHas('ppdbUser.paymentRefundUniform', function ($query) use ($request) {
							if ($request->input('start_date')) {
								$start_date = Carbon::createFromFormat('Y-m-d', $request->input('start_date'))
				        					->startOfDay()
				        					->toDateTimeString();

        						$query = $query->where('updated_at', '>=' , $start_date);
        					}
        					if ($request->input('end_date')) {
        						$end_date = Carbon::createFromFormat('Y-m-d', $request->input('end_date'))
				        					->endOfDay()
				        					->toDateTimeString();

					        	$query = $query->where('updated_at', '<=' , $end_date);
					        }
					        return $query;
						});
        }

        $data = $data->orderBy('id', 'desc')
                    ->paginate();
		return [
			'nav' => $nav,
			'data' => $data,
			'units' => Unit::byUserRole()->get(),
			'params' => $request->only(['name', 'unit', 'page', 'start_date', 'end_date'])
		];
	}

	public function generateAddingData($nav)
	{
		return [
			'nav' => $nav,
			'units' => Unit::byUserRole()->get()
		];
	}


	public function generateEditableData($id, $nav)
	{
		$ppdbResignation = PPDBResignation::where('id', $id)
										->with([
											'unit', 
											'ppdbUser',
											'ppdbUser.user', 
											'ppdbUser.paymentRefunds'
										])->whereHas('ppdbUser', function ($query) {
											return $query->byUserRole();
										})
										->firstOrFail();

		$parentService = new ParentService;
        $mom = $parentService->show(Parents::TYPE_MOTHER, $ppdbResignation->ppdbUser->user_id);
        $dad = $parentService->show(Parents::TYPE_FATHER, $ppdbResignation->ppdbUser->user_id);
        $wali = $parentService->show(Parents::TYPE_WALI, $ppdbResignation->ppdbUser->user_id);

		return [
			'nav' => $nav,
			'data' => $ppdbResignation,
			'dad' => $dad,
            'mom' => $mom,
            'wali' => $wali
		];
	}

	public function getStudent($params)
	{
		if (isset($params['register_number']) && isset($params['unit_id'])) {
			$ppdbUser = PPDBUser::byUserRole()
								->notAccepted()
								->with([
									'unit',
									'user',
								])
								->where('register_number', $params['register_number'])
								->where('unit_id', $params['unit_id'])
								->first();
								
			$data = [];
			if (! is_null($ppdbUser)) {
				$data = [
					'register_number' => $ppdbUser->register_number,
					'name' => $ppdbUser->name,
					'unit' => $ppdbUser->unit->name
				];
			}

			return response()->json(['data' => $data], 200);
		}

		return response()->json(null, 400);
	}

	public function create($params)
	{
		DB::beginTransaction();
		try {
			$ppdbUser = PPDBUser::byUserRole()
								->notAccepted()
								->with([
									'unit', 
									'user', 
								])
								->where('register_number', $params['register_number'])
								->where('unit_id', $params['unit_id'])
								->firstOrFail();


			PPDBResignation::updateOrCreate([
				'ppdb_user_id' => $ppdbUser->id,
				'unit_id' => $ppdbUser->unit_id,
				'register_number' => $ppdbUser->register_number,
			],
			[
				'updated_by_id' => request()->user()->id
			]);

			$ppdbUser->delete();
			DB::commit();

		} catch (\Exception $e) {
			DB::rollback();
			Log::error($e);
			return false;
		}


		return true;
	}

	public function update($id, $params) 
	{
		$ppdbResignation = PPDBResignation::where('id', $id)
										->with([
											'unit', 
											'ppdbUser',
											'ppdbUser.user', 
											'ppdbUser.paymentRefunds'
										])
										->firstOrFail();
		DB::beginTransaction();

		try {
			$params = $this->params($params);

			foreach ($ppdbResignation->ppdbUser->paymentRefunds as $paymentRefund) {
				if (isset($params['nominal_refund'][$paymentRefund->id]))
					$paymentRefund->nominal_refund = $params['nominal_refund'][$paymentRefund->id];

				if (isset($params['note'][$paymentRefund->id]))
					$paymentRefund->note = $params['note'][$paymentRefund->id];

				if (isset($params['refund_image'][$paymentRefund->id])) {
					if (! is_null($paymentRefund->refund_image) && $this->imageExists($paymentRefund->refund_image)) {
						$this->deleteImage($paymentRefund->refund_image);
					}
					$paymentRefund->refund_image = $params['refund_image'][$paymentRefund->id];
				}
				$paymentRefund->save();
			}
			$ppdbResignation->touch();
			$ppdbResignation->save();
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
		if (isset($params['refund_image']) && is_array($params['refund_image']) && request()->hasFile('refund_image')) {
			foreach($params['refund_image'] as $key => $refund_image) {
				if ($image = $this->uploadImage($refund_image)) {
					$params['refund_image'][$key] = $image;
				}
			}
		}

		return $params;
	}

	private function uploadImage($file) {
		$type = 'refund_image';
		if ($upload = $this->doUploadImage($file, $type)) {
			return $upload['path_upload'];
		}

		return false;
	}
}