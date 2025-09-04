<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\YoutubeUrlRule;

class HeadlineRequest extends FormRequest
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
        $content_img = request()->type == "image" ? 'required|mimes:jpeg,jpg,png' : 'nullable';
        $content_url = request()->type == "video" ? ['required', new YoutubeUrlRule()] : 'nullable';
        $unit_id = request()->is_unit == true ? 'required|exists:units,id' : 'nullable';

        if ((request()->method == "PUT" || request()->method == "PATCH")) {
            $content_img = request()->type == "image" ? 'nullable|mimes:jpeg,jpg,png' : 'nullable';
        }

        return [
            'is_unit' => 'required',
            'unit_id' => $unit_id,
            'type' => 'required',
            'content_img' => $content_img,
            'content_url' => $content_url,
            'published' => 'nullable',
            'color_overlay' => 'nullable|string|max:255'
        ];
    }

    public function messages()
    {
        return [
            'content_img.required' => 'Upload gambar wajib diisi',
            'content_url.required'  => 'Youtube url wajib diisi',
            'published.required' => 'Pilih status publish'
        ];
    }
}
