<?php

namespace App\Repositories;

use App\Exceptions\UserNotFoundException;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

class AuthRepository
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function login($data)
    {
        if (!Auth::attempt($data)) {
            throw new UserNotFoundException();
        }
        $user = Auth::user();
        $result['token'] = $user->createToken('Token Name')->accessToken;
        $result['name'] = $user->name;
        return $result;
    }

    public function logout($user)
    {
        try {
            $user->token()->revoke();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
