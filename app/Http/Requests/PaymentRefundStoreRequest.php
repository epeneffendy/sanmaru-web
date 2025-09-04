<?php

namespace App\Http\Requests;

use App\Models\PaymentRefund;
use Illuminate\Foundation\Http\FormRequest;

class PaymentRefundStoreRequest extends FormRequest
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
            'status' => $this->status ?: PaymentRefund::STATUS_NEW_REFUND,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'refund_id' => 'required',
            'refund_type' => 'required|in:registrasi,development,uniform,tuition,other',
            'nominal_refund' => 'required|numeric|min:0',
            'nominal_price' => 'required|numeric|min:0',
            'cause' => 'required',
            'status' => 'required',
            'note' => 'nullable',
            'refund_image' => 'nullable|mimes:jpeg,jpg,png'
        ];

    }


    public function attributes()
    {
        return [
            'refund_code' => 'jenis pengembalian',
            'nominal_price' => 'nominal',
            'nominal_refund' => 'nominal dikembalikan',
            'note' => 'keterangan',
            'refund_image' => 'bukti pengembalian',
            'cause' => 'alasan pengembalian'
        ];
    }
}
