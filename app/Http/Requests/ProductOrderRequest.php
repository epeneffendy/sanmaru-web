<?php

namespace App\Http\Requests;

use App\Models\ProductOrder;
use Illuminate\Foundation\Http\FormRequest;

class ProductOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'invoice_no' => null,
            'payment_type' => null,
            'order_amount' => null,
            'total_payment' => null,
            'status' => $this->status ?: ProductOrder::STATUS_NEW_ORDER,
            'product_order_detail_id' => $this->isMethod('post') ? [] : $this->product_order_detail_id,
            'pickup_status' => $this->pickup_status ?: ProductOrder::PICKUP_STATUS_NOT_PICKUP,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'user_id' => 'required',
            'product_id' => 'required|array',
            'product_id.*' => 'numeric',
            'product_detail_id' => 'required',
            'product_detail_id.*' => 'numeric',
            'product_order_detail_id.*' => 'nullable',
            'size' => 'required|array',
            'size.*' => 'required',
            'price_siswa' => 'required|array',
            'price_siswa.*' => 'required',
            'price_ppdb' => 'required|array',
            'price_ppdb.*' => 'required',
            'qty' => 'required|array',
            'qty.*' => 'numeric',
            'status' => 'required',
            'pickup_status' => 'required',
            'voucher_code' => 'nullable',
        ];

        if($this->isMethod('patch'))
            $rules['product_order_detail_id'] = 'required'; 
        else {
            $rules['product_order_detail_id'] = 'nullable'; 
            $rules['invoice_no'] = 'nullable';
        }
        $rules['payment_type'] = 'nullable';
        $rules['type_tab'] = 'nullable';
        $rules['order_amount'] = 'nullable';
        $rules['total_payment'] = 'nullable';
        return $rules;
    }
}
