<?php

namespace App\Services;

use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Hash;

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

    public function register($request)
    {
        $data = [];
        $data['name'] = $request['name'];
        $data['email'] = $request['email'];
        $data['role'] = 'user';
        $data['password'] = Hash::make($request['password']);
        $data['avatar'] = 'defaultAvatar.png';
        $data['panel'] = 'defaultPanel.png';
        return $this->authRepository->register($data);
    }

    public function logout($user)
    {
        return $this->authRepository->logout($user);
    }
}
