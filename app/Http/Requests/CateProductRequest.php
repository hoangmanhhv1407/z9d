<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CateProductRequest extends FormRequest
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
            'cpr_name'        => 'required',
        ];
    }

    public function messages()
    {
        return [
            'cpr_name.required'        => ' Please enter a title',
        ];
    }
}
