<?php

namespace App\Http\Requests;

use App\Models\Vendor;
use Illuminate\Foundation\Http\FormRequest;

class VendorStoreRequest extends FormRequest
{
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
            'address' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', "unique:users,email,{$this->userId()}"],
            'city' => ['required', 'string'],
            'pic' => ['required', 'string'],
            'mobile_phone' => ['required','phone:ID', "unique:users,mobile_phone,{$this->userId()}"],
            'nota_number' => ['required'],
            'nota_date' => ['required', 'date']
        ];
    }

    private function userId()
    {
        if($this->id !== null)
        {
            return Vendor::where('id', $this->id)->limit(1)->pluck('user_id')->first();
        }
    }
}
