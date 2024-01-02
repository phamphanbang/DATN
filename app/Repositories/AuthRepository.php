<?php

namespace App\Repositories;

use App\Constants;
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
            throw new UserNotFoundException(__('exceptions.authenFail'));
        }
        return $this->userLogin($data);
    }

    public function adminLogin($data)
    {
        if (!Auth::attempt($data)) {
            throw new UserNotFoundException('Wrong email or password');
        }
        return $this->userLogin($data);
    }

    public function userLogin($data)
    {
        $user = Auth::user();
        $result['token'] = $user->createToken('Token Name')->accessToken;
        $result['name'] = $user->name;
        $isAdmin = $user->role === 'superadmin' || $user->role === 'admin';
        $result['isAdmin'] = $isAdmin ? 'true' : 'false';
        $result['avatar'] = $user->avatar ?? 'defaultAvatar.png';
        $result['id'] = $user->id;
        return $result;
    }

    public function register($data)
    {
        $user = $this->user->create($data);
        $result['token'] = $user->createToken('Token Name')->accessToken;
        $result['name'] = $user->name;
        $isAdmin = $user->role === 'superadmin' || $user->role === 'admin';
        $result['isAdmin'] = $isAdmin ? 'true' : 'false';
        $result['avatar'] = $user->avatar ?? 'defaultAvatar.png';
        $result['id'] = $user->id;
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
