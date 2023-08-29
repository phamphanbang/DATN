<?php

namespace App\Exceptions;

use Exception;

class UserNotAuthorizeException extends Exception
{
    public function render()
    {
        $response = [
            'status' => 'fail',
            'message' => "Bạn không đủ quyền để thực hiện hành đọng này",
        ];
        return $response;
    }
}
