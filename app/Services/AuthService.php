<?php

namespace App\Services;

use App\Repositories\AuthRepository;

class AuthService
{
    private $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function login($data)
    {
        return $this->authRepository->login($data);
    }

    public function logout($user)
    {
        return $this->authRepository->logout($user);
    }
}
