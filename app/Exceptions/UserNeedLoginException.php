<?php

namespace App\Exceptions;

use Exception;

class UserNeedLoginException extends Exception
{
    public function render()
    {
        $response = [
            'status' => 'fail',
            'message' => "Người dùng cần đăng nhập",
        ];
        return $response;
    }
}
