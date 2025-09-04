<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\InputCollectionHelper;
use App\Models\Classes;

class StudentStoreRequest extends FormRequest
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
            'father_name' => $this->input('f_name')?: null,
            'mother_name' => $this->input('m_name')?: null,
            'wali_name' => $this->input('w_name')?: null,
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
            'nis' => ['required', 'numeric', "unique:students,nis,{$this->id}"],
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required'],
            'email' => ['required', 'email', 'string', 'max:255', "unique:students,email,{$this->id}"],
            'mobile_phone' => ['required', 'phone:ID', "unique:students,mobile_phone,{$this->id}"],
            'school_year' => ['required', 'numeric'],
            'class_id' => ['required', 'exists:classes,id'],
            'image' => ['nullable', 'mimes:jpeg,jpg,png'],
            'register_number' => ['required', 'string'],
            'payment_agreement_id' => ['nullable', 'exists:payment_agreements,id'],
            'status' => ['nullable'],
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
            'nik' => ['nullable'],
            'additional_info' => ['nullable'],
        ]);

        $unit = Classes::find($this->input('class_id'));
        $additionalRules = InputCollectionHelper::additionalData(null, $unit)->all();
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
}
