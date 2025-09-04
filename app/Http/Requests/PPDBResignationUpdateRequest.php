<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PPDBResignationUpdateRequest extends FormRequest
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

    public function rules()
    {
        return [
            'id' => 'required|exists:ppdb_resignations,id',
            'refund_code.*' => 'required|in:registrasi,development,uniform,tuition,other',
            'nominal_refund.*' => 'required|numeric|min:0',
            //'nominal_price.*' => 'required|numeric|min:0',
            'note.*' => 'nullable',
            'refund_image.*' => 'nullable|mimes:jpeg,jpg,png'
        ];

    }


    public function attributes()
    {
        return [
            'refund_code.*' => 'jenis pengembalian',
            'nominal_price.*' => 'nominal',
            'nominal_refund.*' => 'nominal dikembalikan',
            'note.*' => 'keterangan',
            'refund_image.*' => 'bukti pengembalian'
        ];
    }
}
