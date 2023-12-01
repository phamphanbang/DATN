<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

class UserRegisterRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:users,name|max:20|min:4',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|max:20|min:8',
            'confirm_password' => 'required|same:password'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên người dùng không được bỏ trống',
            'name.max' => 'Tên người dùng không được quá 20 ký tự',
            'name.min' => 'Tên người dùng không được ít hơn 4 ký tự',
            'name.unique' => 'Tên đã có người sử dụng',
            'email.required' => 'Email không được bỏ trống',
            'email.email' => 'Email không hợp lệ',
            'password.required' => 'Mật khẩu không được bỏ trống',
            'password.max' => 'Mật khẩu không được quá 20 ký tự',
            'password.min' => 'Mật khẩu không được ít hơn 8 ký tự',
            'confirm_password.same' => 'Mật khẩu xác nhận không trúng khớp'
        ];
    }
}
