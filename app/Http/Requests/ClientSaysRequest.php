<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientSaysRequest extends FormRequest
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
            'cs_name'        => 'required',
            'cs_thunbar'        => 'required',
            'cs_content'        => 'required',
            'cs_service'        => 'required',
        ];
    }

    public function messages()
    {
        return [
            'cs_name.required'        => ' Please enter a title',
            'cs_thunbar.required'        => ' Please select an image',
            'cs_content.required'        => ' Please enter the content',
            'cs_service.required'        => ' Please enter the service',
        ];
    }
}
