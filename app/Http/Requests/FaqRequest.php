<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FaqRequest extends FormRequest
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
            'published' => $this->has('published')
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|min:10|max:255',
            'content' => 'required',
            'published' => 'required',
            'publish_date' => 'required|date',
            'tags' => 'nullable',
            'answer' => 'required',
            'category' => 'nullable|in:web-school,web-PPDB',
        ];
    }

    public function messages()
    {
        return [
            'content.required'  => 'Pertanyaan wajib diisi',
            'answer.required' => 'Jawaban wajib diisi'
        ];
    }
}
