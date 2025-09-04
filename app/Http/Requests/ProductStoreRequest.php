<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'sizes' => ['required'],
            'prices_siswa' => ['required'],
            'prices_siswa.*' => ['numeric'],
            'prices_vendor_regular' => ['required'],
            'prices_vendor_regular.*' => ['numeric'],
            'prices_ppdb' => ['required'],
            'prices_ppdb.*' => ['numeric'],
            'prices_vendor_ppdb' => ['required'],
            'prices_vendor_ppdb.*' => ['numeric'],
            'level' => ['required'],
            'stocks' => ['required'],
            'stocks.*' => ['numeric'],
            'units' => ['required', 'array'],
            'product_details_ids' => ['required'],
            'product_details_ids.*' => ['numeric', 'nullable'],
            'merk' => ['required'],
            'slug' => ['string', "unique:products,slug,{$this->id}"],
            'status' => ['required', 'in:published,unpublished'],
            'category' => ['required'],
            'type' => ['required'],
            'weight' => ['required', 'numeric'],
            'image' => ['nullable', 'mimes:jpeg,jpg,png'],
            'description' => ['nullable'],
            'vendor_id' => ['numeric', 'nullable']
        ];
    }
}
