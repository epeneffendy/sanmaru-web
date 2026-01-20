<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoucherStoreRequest extends FormRequest
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
            'rule' => $this->type === 'free_product' ? json_encode($this->product_id) : ($this->type === 'discount_fixed' ? $this->discount_fixed : $this->discount_percent),
            'usage_limit' => $this->usage_limit_option_all ? -1 : $this->usage_limit,
            'active' => $this->active ? 1 : 0,
            'unit_id' => $this->target === 'unit' ? $this->unit_id : null,
            'user_id' => $this->target === 'student' ? $this->user_id : null
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $excludeCode = $this->id ? [
            'required', 'string', 'unique:vouchers,code,'. $this->id
        ] : [
            'required', 'string', 'unique:vouchers,code'
        ];

        return [
            'id' => 'numeric|nullable',
            'code' => $excludeCode,
            'type' => 'required|in:free_product,discount_fixed,discount_percent',
            'product_id' => 'required_if:type,free_product|array|nullable',
            'product_id.*' => 'numeric',
            'discount_fixed' => 'required_if:type,discount_fixed|numeric|min:1|nullable',
            'discount_percent' => 'required_if:type,discount_percent|numeric|min:1|max:100|nullable',
            'target' => 'required|in:all,unit,student',
            'unit_id' => 'required_if:target,unit|array|nullable',
            'unit_id.*' => 'numeric',
            'user_id' => 'required_if:target,student|array|nullable',
            'user_id.*' => 'numeric',
            'usage_limit_option_all' => 'nullable|boolean',
            'usage_limit' => 'required_unless:usage_limit_option_all,1|min:1',
            'usage_type' => 'required|in:cumulative,per_user',
            'active' => 'nullable|boolean',
            'rule' => 'string|required',
            'note' => 'nullable|string',
            'year' => 'nullable|integer',
            'target_siswa' => 'nullable|string',
            'period_id' => 'nullable|integer',
            'unit_student' => 'nullable|integer',
        ];
    }

    public function messages()
    {
        return [
            'type.in' => 'Tipe Voucher salah',
            'product_id.required_if' => 'Daftar produk harus diisi jika memilih voucher tipe "Produk Gratis"',
            'discount_fixed.required_if' => 'Potongan harga harus diisi jika memilih voucher tipe "Potongan Harga"',
            'discount_percent.required_if' => 'Potongan dalam persen harus diisi jika memilih voucher tipe "Diskon Persen"',
            'unit_id.required_if' => 'Unit harus diisi jika memilih Target voucher "Khusus Unit"',
            'user_id.required_if' => 'Siswa harus diisi jika memilih Target voucher "Khusus Siswa"',
            'usage_limit.required_unless' => 'Batas jumlah pengguaan harus diisi jika tidak memilih "Tidak ada batas"',
        ];
    }
}
