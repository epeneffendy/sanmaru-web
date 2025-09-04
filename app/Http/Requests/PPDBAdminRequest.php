<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\InputCollectionHelper;
use App\Models\PPDBUser;
use App\Models\Unit;

class PPDBAdminRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->input('f_name')) {
            $this->merge([
                'father_name' => $this->input('f_name')
            ]);
        }

        if ($this->input('m_name')) {
            $this->merge([
                'mother_name' => $this->input('m_name')
            ]);
        }
        if ($this->input('w_name')) {
            $this->merge([
                'wali_name' => $this->input('w_name')
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'mobile_phone' => ['required', 'phone:ID'],
            'unit_id' => ['required', 'exists:units,id'],
            'periode' => ['required', 'exists:periods,id'],
            'register_number' => ['required', 'numeric', "unique:ppdb_users,register_number,{$this->userId()},id"],
            'origin_school' => ['nullable', 'string', 'max:255'],
            'class_option' => ['nullable', 'string', 'max:255'],
            'password' => ['string', 'required_without:id'],
            'password_confirmation' => ['string', 'required_without:id', 'same:password'],
            'send_confirmation' => ['nullable']
        ];

        //additional student
        $rules = array_merge($rules, [
            'gender' => ['nullable'],
            'place_of_birth' => ['nullable'],
            'date_of_birth' => ['nullable'],
            'address' => ['nullable'],
            'city' => ['nullable'],
            'region' => ['nullable'],
            'country' => ['nullable'],
            'religion' => ['nullable'],
            // 'nik' => ['nullable'],
            'additional_info' => ['nullable'],
        ]);

        $unit = Unit::find($this->input('unit_id'));
        $additionalRules = InputCollectionHelper::additionalData($unit)->all();
        foreach ($additionalRules as $keys => $values) {
            foreach ($values as $key => $value) {
                if ($value == 'required') {
                    $additionalRules[$keys][$key] = 'nullable';
                }
            }
        }

        $rules = array_merge($rules, $additionalRules);

        $prefix = ['f', 'm', 'w'];
        foreach ($prefix as $parent) {
            if ($parent == 'f' && $this->input('tinggal_dengan') === 'wali') continue;
            if ($parent == 'm' && $this->input('tinggal_dengan') === 'wali') continue;
            if ($parent == 'w' && $this->input('tinggal_dengan') !== 'wali') continue;
            $rules[$parent.'_name'] = ['nullable', 'max:255'];
            $rules[$parent.'_phone'] = ['required_with:'.$parent.'_name', 'nullable', 'phone:ID,mobile'];
            $rules[$parent.'_place_of_birth'] = ['required_with:'.$parent.'_name', 'nullable'];
            $rules[$parent.'_date_of_birth'] = ['required_with:'.$parent.'_name', 'nullable', 'date'];
            $rules[$parent.'_address'] = ['required_with:'.$parent.'_name', 'nullable'];
            $rules[$parent.'_city'] = ['required_with:'.$parent.'_name', 'nullable'];
            $rules[$parent.'_region'] = ['required_with:'.$parent.'_name', 'nullable'];
            $rules[$parent.'_country'] = ['required_with:'.$parent.'_name', 'nullable'];
            $rules[$parent.'_religion'] = ['required_with:'.$parent.'_name', 'nullable'];
            $rules[$parent.'_job'] = ['required_with:'.$parent.'_name', 'nullable'];
            $rules[$parent.'_salary'] = ['required_with:'.$parent.'_name', 'nullable'];
            $rules[$parent.'_education'] = ['required_with:'.$parent.'_name', 'nullable'];
        }

        $rules['father_name'] = $rules['mother_name'] = $rules['wali_name'] = ['nullable', 'max:255'];
        $rules['payment_form'] = ['nullable', 'file', 'mimes:jpg,jpeg,png'];
        $rules['birth_certificate'] = ['nullable', 'file', 'mimes:jpg,jpeg,png'];
        $rules['photo'] = ['nullable', 'file', 'mimes:jpg,jpeg,png'];
        $rules['family_card'] = ['nullable', 'file', 'mimes:jpg,jpeg,png'];
        $rules['parent_identity_card'] = ['nullable', 'file', 'mimes:jpg,jpeg,png'];
        $rules['marriage_certificate'] = ['nullable', 'file', 'mimes:jpg,jpeg,png'];
        $rules['report_cards'] = ['nullable', 'array'];
        $rules['report_cards.*'] = ['nullable', 'file', 'mimes:jpg,jpeg,png'];
        $rules['award_photo'] = ['nullable', 'file', 'mimes:jpg,jpeg,png'];
        $rules['kartu_golongan_darah'] = ['nullable', 'file', 'mimes:jpg,jpeg,png'];
        $rules['kms'] = ['nullable', 'file', 'mimes:jpg,jpeg,png'];
        $rules['baptismal_certificate'] = ['nullable', 'file', 'mimes:jpg,jpeg,png'];
        $rules['rekomendasi_bk'] = ['nullable', 'file', 'mimes:jpg,jpeg,png'];
        $rules['nilai_raport'] = ['nullable', 'string'];
        $rules['angket_peminatan'] = ['nullable', 'file', 'mimes:jpg,jpeg,png'];
        $rules['statement_letter'] = ['nullable', 'file', 'mimes:jpg,jpeg,png'];

        return $rules;
    }

    private function userId()
    {
        if ($this->id !== null)
        {
            return PPDBUser::where('id', $this->id)->limit(1)->pluck('user_id')->first();
        }
    }
}
