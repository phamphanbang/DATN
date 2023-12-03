<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class UserUpdateRequest extends BaseRequest
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
            'name' => 'required|max:20|min:8|unique:users,name,' . request()->route('user'),
            'email' => 'required|email|unique:users,email,' . request()->route('user'),
            'avatar' => $this->getValidationRule('avatar'),
            'panel' => 'nullable|image',
            'role' => 'nullable|in:admin,user'
        ];
    }

    public function getValidationRule(String $key): string
    {
        if (request()->hasFile($key)) {
            return "nullable|mimes:png,jpg";
        }
        return "nullable|string";
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
