<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class BlogRequest extends FormRequest
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
            'b_name' => [
                'required',
                'string',
                'max:255',
                'min:3'
            ],
            'b_content' => [
                'required',
                'string',
                'min:10'
            ],
            'b_category_id' => [
                'required',
                'integer',
                'exists:category_blog,id'  // Đảm bảo category tồn tại trong database
            ],
        ];
        // Kiểm tra nếu là request tạo mới thì bắt buộc phải có ảnh
        // Nếu là request update thì ảnh là tùy chọn
        if ($this->isMethod('POST')) {
            $rules['b_thunbar'] = [
                'required',
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:10240', // Đã tăng lên 10MB
                // Đã xóa giới hạn kích thước dimensions
            ];
        } else {
            $rules['b_thunbar'] = [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:10240', // Đã tăng lên 10MB
                // Đã xóa giới hạn kích thước dimensions
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
            'b_name.required' => 'Please enter a title',
            'b_name.string' => 'Title must be a string',
            'b_name.max' => 'Title cannot exceed 255 characters',
            'b_name.min' => 'Title must be at least 3 characters',
            'b_thunbar.required' => 'Please select an image',
            'b_thunbar.image' => 'The file must be an image',
            'b_thunbar.mimes' => 'Only JPEG, PNG, JPG and GIF files are allowed',
            'b_thunbar.max' => 'Image size cannot exceed 10MB', // Đã cập nhật thông báo
            // Đã xóa thông báo về dimensions
            'b_content.required' => 'Please enter the content',
            'b_content.string' => 'Content must be text',
            'b_content.min' => 'Content must be at least 10 characters',
            'b_category_id.required' => 'Please select a category',
            'b_category_id.integer' => 'Invalid category',
            'b_category_id.exists' => 'Selected category does not exist',
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
        $validator->after(function ($validator) {
            // Thêm custom validation nếu cần
            // Ví dụ: kiểm tra kích thước file thực tế
            if ($this->hasFile('b_thunbar')) {
                $file = $this->file('b_thunbar');
                if ($file->getSize() > 10240 * 1024) { // Đã tăng lên 10MB
                    $validator->errors()->add('b_thunbar', 'Image size exceeds 10MB limit'); // Đã cập nhật thông báo
                }
            }
        });
    }
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Trim whitespace from text inputs
        if ($this->has('b_name')) {
            $this->merge([
                'b_name' => trim($this->b_name)
            ]);
        }
    }
}