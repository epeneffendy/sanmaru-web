<?php

namespace App\Http\Requests;

use App\Enums\ProductScheduleTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class ProductKantinStoreRequest extends FormRequest
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
            'stocks' => ['required'],
            'stocks.*' => ['numeric'],
            'units' => ['required', 'array'],
            'product_details_ids' => ['required'],
            'product_details_ids.*' => ['numeric', 'nullable'],
            'slug' => ['string', "unique:products,slug,{$this->id}"],
            'status' => ['required', 'in:published,unpublished'],
            'category' => ['required'],
            'stand' => ['required'],
            'type' => ['required'],
            'schedule.type' => ['required'],
            'schedule.available_on' => ['required', 'array'],
            'schedule.available_on.open_date' => ['required_if:schedule.type,' . ProductScheduleTypeEnum::PREORDER],
            'schedule.available_on.close_date' => ['required_if:schedule.type,' . ProductScheduleTypeEnum::PREORDER],
            'schedule.available_on.pickup_date_schedule' => ['required_if:schedule.type,' . ProductScheduleTypeEnum::PREORDER],
            'schedule.available_on.pickup_start_time' => ['required_if:schedule.type,' . ProductScheduleTypeEnum::PREORDER],
            'schedule.available_on.pickup_end_time' => ['required_if:schedule.type,' . ProductScheduleTypeEnum::PREORDER],
            'schedule.available_on.pickup_location' => ['required_if:schedule.type,' . ProductScheduleTypeEnum::PREORDER],
            'schedule.available_on.pickup_notes' => ['nullable'],
            'image' => ['nullable', 'mimes:jpeg,jpg,png'],
            'description' => ['nullable'],
            'weight' => ['nullable'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'prices_ppdb' => $this->get('prices_siswa', []),
            'prices_vendor_ppdb' => $this->get('prices_siswa', []),
            'prices_vendor_regular' => $this->get('prices_siswa', []),
            'weight' => 0,
        ]);
    }
}
