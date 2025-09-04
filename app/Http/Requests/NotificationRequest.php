<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotificationRequest extends FormRequest
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
            'year' => ['required', 'numeric'],
            'unit' => ['required', 'numeric'],
            'periode' => ['required', 'numeric'],
            'ppdb_user_id' => ['required', 'array'],
            'ppdb_user_id.*' => ['required_with:details', 'numeric'],
            'title' => ['required'],
            'body' => ['required'],
            'send_email' => ['boolean'],
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
            'send_email' => $this->get('send_email', 0),
        ]);
    }



    public function attributes()
    {
        return [
            'year' => 'Tahun Ajaran',
            'unit' => 'Unit',
            'periode' => 'Periode',
            'ppdb_user_id' => 'Penerima',
            'title' => 'Judul',
            'body' => 'Pesan',
        ];
    }
}
