<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

class UserLoginRequest extends BaseRequest
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
            'email' => 'required|email',
            'password' => 'required|string|max:20|min:8',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email không được bỏ trống',
            'email.email' => 'Email không hợp lệ',
            'password.required' => 'Mật khẩu không được bỏ trống',
            'password.max' => 'Mật khẩu không được quá 20 ký tự',
            'password.min' => 'Mật khẩu không được ít hơn 8 ký tự'
        ];
    }
}
