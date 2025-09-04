<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\OngoingUnitPeriodsRule;
use App\Rules\ClassOptionRule;
use App\Rules\AgeLimitRule;
use App\Models\Unit;

class PPDBRegisterRequest extends FormRequest
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
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'mobile_phone' => ['required', 'phone:ID'],
            'password' => ['required', 'min:8', 'confirmed'],
            'unit_id' => ['required', new OngoingUnitPeriodsRule],
            'date_of_birth' => [new AgeLimitRule(request()->unit_id, request())],
            'origin_school' => ['nullable'],
            'periode' => ['required', 'exists:periods,id'],

            // Task https://aimsis.atlassian.net/browse/AIMSIS-10511
            // Menambah validasi NIK siswa dan NIK ortu harus unique di satu unit yg sama
            // Kalo unit berbeda (misal sudah daftar di SD dan SMP), NIK gapapa sama
            // Code syntax ditemukan di sini https://stackoverflow.com/questions/50091538/unique-two-columns-in-laravel
            // Dokumentasi resmi di sini https://laravel.com/docs/5.8/validation#rule-unique
            'nik_siswa' => ['required', 'numeric', 'digits:16'],
            'nik_ortu' => ['required', 'numeric', 'digits:16']
        ];

        $unit = Unit::where('id', request()->unit_id)->first();
        if ($unit && in_array($unit->name, ['KB-SURABAYA', 'TK-SURABAYA', 'TK-SIDOARJO'])) {
            $rules['class_option'] = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama Lengkap harus diisi.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
            'class_option.required' => 'Pilihan kelas harus diisi',
            'mobile_phone.required' => 'Nomor telepon harus diisi',
            'mobile_phone.unique' => 'Nomor telepon sudah terdaftar',
            'mobile_phone.phone' => 'Format nomor telepon tidak sesuai',
            'nik_siswa.required' => 'NIK Siswa harus diisi',
            'nik_siswa.numeric' => 'Format NIK Siswa harus berupa angka',
            'nik_siswa.digits' => 'Penginputan form NIK Siswa wajib minimal 16 digit',
            'nik_siswa.unique' => 'NIK Siswa sudah terdaftar',
            'nik_ortu.required' => 'NIK Orang Tua harus diisi',
            'nik_ortu.numeric' => 'Format NIK Orang Tua harus berupa angka',
            'nik_ortu.digits' => 'Penginputan form NIK Orang Tua wajib minimal 16 digit',
            'nik_ortu.unique' => 'NIK Orang Tua sudah terdaftar'
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all();
        try {
            $data['mobile_phone'] = app('phoneNormalizerService')->normalize($data['mobile_phone']);
        } catch (\Exception $exception) {
            // ignore error, will be handled by the validator
        }
        return $data;
    }
}
