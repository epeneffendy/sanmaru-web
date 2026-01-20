<?php

namespace App\Services;

use App\Models\ComplaintOrders;
use App\Traits\ImageHandler;

class ProductOrderComplaintService
{
    use ImageHandler;

    public function store($params, $payload)
    {
        $success = true;
        $message = '';
        $attachment = $attachment_addition = $attachment_extra = '';

        if (isset($params['attachment']) && $params['attachment'] && $image = $this->uploadImage(request(), $params, 'attachment')) {
            $attachment = $image;
        }

        if (isset($params['attachment_addition']) && $params['attachment_addition'] && $image = $this->uploadImage(request(), $params, 'attachment_addition')) {
            $attachment_addition = $image;
        }

        if (isset($params['attachment_extra']) && $params['attachment_extra'] && $image = $this->uploadImage(request(), $params, 'attachment_extra')) {
            $attachment_extra = $image;

        }


        $complaintOrder = new ComplaintOrders();
        $complaintOrder->user_id = $payload['user_id'];
        $complaintOrder->student_type = $payload['type'];
        $complaintOrder->complaint_category_id = $params['complaint_category_id'];
        $complaintOrder->product_order_id = $payload['product_order_id'];
        $complaintOrder->product_order_detail_id = $params['product_id'];
        $complaintOrder->product_detail_id = $payload['product_detail_id'];
        $complaintOrder->product_id = $payload['product_id'];
        $complaintOrder->phone = $params['phone'];
        $complaintOrder->email = $params['email'];
        $complaintOrder->description = $params['complaint'];
        $complaintOrder->attachment = $attachment;
        $complaintOrder->attachment_addition = (!empty($attachment_addition)) ? $attachment_addition : null;
        $complaintOrder->attachment_extra = (!empty($attachment_extra)) ? $attachment_extra : null;

        if (!$complaintOrder->save()) {
            $success = false;
            $message = 'Data gagal disimpan!';
        }
        return ['success' => $success, 'message' => $message];
    }

    private function uploadImage($request, $params, $flag)
    {

        if ($request->hasFile($flag)) {
            if ($upload = $this->doUploadImage($request->file($flag), $flag)) {
                return $upload['path_upload'];
            }
        }
        return false;
    }
}
