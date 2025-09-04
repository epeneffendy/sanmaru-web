<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UniformOverpaymentRule;

class UniformOverpaymentStoreRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {   
        return [
            'register_number' => 'required|exists:ppdb_users,register_number',
            'invoice_no' => ['required','exists:product_orders,invoice_no'],
            'unit_id' => 'required|exists:units,id',
            'grand_total' => 'required|numeric',
            'payment_number' => ['required', new UniformOverpaymentRule($this->input())],
            'payment_name' => 'required',
            'payment_date' => 'required|date',
            'payment_method' => 'required',
            'payment_amount' => 'required|numeric|gt:grand_total',
            
            'status' => 'required',
            'note' => 'nullable',
            'nominal_refund' => ['required','numeric', new UniformOverpaymentRule($this->input())],
            'refund_image' => 'nullable|mimes:jpeg,jpg,png'
        ];
    }

    public function attributes()
    {
        return [
            'nominal_refund' => 'Nominal pengembalian',
            'payment_number' => 'Virtual Account Number',
            'payment_amount' => 'Nominal yang dibayar',
            'payment_date' => 'Tanggal pembayaran',
            'payment_name' => 'Virtual Account Name',
            'payment_method' => 'Metode pembayaran',
            'unit_id' => 'unit_id',
            'register_number' => 'Nomor registrasi',
            'grand_total' => 'Nominal harus bayar',
            'invoice_no' => 'Nomor pembayaran'
        ];
    }
}
