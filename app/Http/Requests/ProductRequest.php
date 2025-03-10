<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'prd_name' => [
                'required',
                'string',
                'max:255',
                'min:3'
            ],
            'prd_content' => [
                'required',
                'string',
                'min:10'
            ],
            'prd_category_product_id' => [
                'required',
                'integer',
                'exists:category_product,id'
            ],
            'prd_code' => [
                'required',
                'string',
                'max:50',
                'unique:products,prd_code,' . $this->id
            ],
            'coin' => [
                'required',
                'numeric',
                'min:0'
            ],
            'prd_description' => [
                'nullable',
                'string'
            ],
            'turn' => [
                'nullable',
                'integer',
                'min:0'
            ],
            'ratioLucky' => [
                'nullable',
                'numeric',
                'between:0,32'
            ],
            'accumulate_status' => [
                'required',
                'in:0,1'
            ],
            'accu' => [
                'required_if:accumulate_status,1',
                'nullable',
                'numeric',
                'min:0'
            ],
            'prd_status' => [
                'required',
                'in:0,1'
            ]
        ];

        // Đã loại bỏ các giới hạn về ảnh (kích thước, định dạng, kích thước file)
        if ($this->isMethod('POST')) {
            $rules['prd_thunbar'] = [
                'required',
            ];
        } else {
            $rules['prd_thunbar'] = [
                'nullable',
            ];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'prd_name.required' => 'Bạn chưa nhập tiêu đề',
            'prd_name.string' => 'Tiêu đề phải là chuỗi ký tự',
            'prd_name.max' => 'Tiêu đề không được vượt quá 255 ký tự',
            'prd_name.min' => 'Tiêu đề phải có ít nhất 3 ký tự',

            'prd_thunbar.required' => 'Bạn chưa chọn ảnh',

            'prd_content.required' => 'Bạn chưa nhập nội dung',
            'prd_content.string' => 'Nội dung phải là chuỗi ký tự',
            'prd_content.min' => 'Nội dung phải có ít nhất 10 ký tự',

            'prd_category_product_id.required' => 'Bạn chưa chọn danh mục',
            'prd_category_product_id.integer' => 'ID danh mục không hợp lệ',
            'prd_category_product_id.exists' => 'Danh mục không tồn tại',

            'prd_code.required' => 'Bạn chưa nhập mã sản phẩm',
            'prd_code.string' => 'Mã sản phẩm phải là chuỗi ký tự',
            'prd_code.max' => 'Mã sản phẩm không được vượt quá 50 ký tự',
            'prd_code.unique' => 'Mã sản phẩm đã tồn tại',

            'coin.required' => 'Bạn chưa nhập số xu',
            'coin.numeric' => 'Số xu phải là số',
            'coin.min' => 'Số xu phải lớn hơn hoặc bằng 0',

            'turn.integer' => 'Lượt mua phải là số nguyên',
            'turn.min' => 'Lượt mua không được âm',

            'ratioLucky.numeric' => 'Tỷ lệ phải là số',
            'ratioLucky.between' => 'Tỷ lệ phải nằm trong khoảng 0-100',

            'accumulate_status.required' => 'Bạn chưa chọn trạng thái tích lũy',
            'accumulate_status.in' => 'Trạng thái tích lũy không hợp lệ',

            'accu.required_if' => 'Bạn chưa nhập điểm tích lũy',
            'accu.numeric' => 'Điểm tích lũy phải là số',
            'accu.min' => 'Điểm tích lũy không được âm',

            'prd_status.required' => 'Bạn chưa chọn trạng thái',
            'prd_status.in' => 'Trạng thái không hợp lệ'
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        // Đã loại bỏ phần kiểm tra kích thước ảnh
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->has('prd_name')) {
            $this->merge([
                'prd_name' => trim($this->prd_name)
            ]);
        }
        
        if ($this->has('prd_code')) {
            $this->merge([
                'prd_code' => trim($this->prd_code)
            ]);
        }
    }
}