<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class BaseRequest extends FormRequest {

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->validateError($validator->errors(), Response::HTTP_BAD_REQUEST));
    }
}
