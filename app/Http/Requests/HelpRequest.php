<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HelpRequest extends FormRequest
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
            'h_name'        => 'required',
            'h_thunbar'        => 'required',
            'h_content'        => 'required',
            'h_category_id'        => 'required',
        ];
    }

    public function messages()
    {
        return [
            'h_name.required'        => ' Please enter a title',
            'h_thunbar.required'        => ' Please select an image',
            'h_content.required'        => ' Please enter the content',
            'h_category_id.required'        => ' Please enter the category',
        ];
    }
}
