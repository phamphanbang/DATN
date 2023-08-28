<?php

namespace App\Exceptions;

use Exception;

class UserNotFoundException extends Exception
{
    public function report()
    {
    }

    public function render()
    {
        $response = [
            'status' => 'fail',
            'message' => "Mật khẩu hoặc tài khoản không đúng",
        ];
        return $response;
    }
}
