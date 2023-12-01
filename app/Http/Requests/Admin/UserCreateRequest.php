<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class UserCreateRequest extends BaseRequest
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
            'avatar' => 'nullable|image',
            'panel' => 'nullable|image',
            'role' => 'required|in:admin,user'
        ];
    }

    // public function messages()
    // {
    //     return [
    //         'name.required' => __('valid.user.name.required'),
    //         'name.unique' => __('valid.user.name.unique'),
    //         'name.max' => __('valid.user.name.max'),
    //         'name.min' => __('valid.user.name.min'),
    //         'email.required' => __('valid.user.email.required'),
    //         'email.email' =>__('valid.user.email.email'),
    //         'email.unique' => __('valid.user.email.unique'),
    //         'password.required' => __('valid.user.password.required'),
    //         'password.string' => __('valid.user.password.string'),
    //         'password.min' => __('valid.user.password.min'),
    //         'password.max' => __('valid.user.password.max'),
    //         'avatar.image' => __('valid.user.avatar.image'),
    //         'panel.image' => __('valid.user.panel.image')
    //     ];
    // }

}
