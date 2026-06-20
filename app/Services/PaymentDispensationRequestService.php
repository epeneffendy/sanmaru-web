<?php

namespace App\Services;

use App\Helpers\PriceHelper;
use App\Lib\DbTrx;
use App\Models\PaymentDispensationDetails;
use App\Models\PaymentDispensationRequest;
use App\Models\PPDBUser;
use App\Models\StudentBills;
use Carbon\Carbon;
use App\Traits\ImageHandler;

class PaymentDispensationRequestService {

    use ImageHandler;

    public function get()
    {
        $data = PaymentDispensationRequest::get();
        return $data;
    }

    public function find($id){
        $data = PaymentDispensationRequest::find($id);
        return $data;
    }

    public function findByPppdbUser($id){
        $data = PaymentDispensationRequest::where([
            'ppdb_user_id'=>$id,
        ])->orderBy('id', 'desc')->first();
        return $data;
    }

    public function store($params, $data){
        
        $success = true;
        $message = '';
        $attachment ='';

        if (isset($params['attachment']) && $params['attachment'] && $image = $this->uploadImage(request(), $params)) {
            $attachment = $image;
        }

        $request = new PaymentDispensationRequest();
        $request->ppdb_user_id = $params['ppdb_user_id'];
        $request->unit_id = $params['unit_id'];
        $request->school_year = $params['school_year'];
        $request->dispensation_type = $params['dispensation_type'];
        $request->attachment = $attachment;
        $request->status = $params['status'];
        $request->reason = $params['reason'];

        if (!$request->save()) {
            $success = false;
            $message = 'Data gagal disimpan!';
        }
        
        return ['success' => $success, 'message' => $message];
    }


    private function uploadImage($request, $params)
    {
        if ($request->hasFile('attachment')) {
            if ($upload = $this->doUploadImage($request->file('attachment'), 'attachment')) {
                return $upload['path_upload'];
            }
        }
        return false;
    }


}
