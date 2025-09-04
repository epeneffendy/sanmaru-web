<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\YoutubeUrlRule;

class VoiceOfSanmarRequest extends FormRequest
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
            'title' => 'required',
            'content_url' => ['required', new YoutubeUrlRule]
        ];
    }

    public function messages()
    {
        return [
            'content_url.required' => 'Youtube url wajib diisi'
        ];
    }
}
