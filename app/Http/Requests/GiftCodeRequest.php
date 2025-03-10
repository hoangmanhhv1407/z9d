<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GiftCodeRequest extends FormRequest
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
            'giftcode'        => 'required',
            'qty'        => 'required',
            'content'        => 'required',
        ];
    }

    public function messages()
    {
        return [
            'giftcode.required'        => 'Bạn chưa nhập giftcode',
            'qty.required'        => ' Bạn chưa nhập số lượng',
            'content.required'        => ' Bạn chưa nhập nội dung',
        ];
    }
}
