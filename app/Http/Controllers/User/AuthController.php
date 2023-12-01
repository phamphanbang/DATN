<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserLoginRequest;
use App\Http\Requests\User\UserRegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Throwable;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(UserLoginRequest $request)
    {
        $data = $request->only('email', 'password');
        $result = $this->authService->login($data);
        return response()->success($result, Response::HTTP_OK);
    }

    public function register(UserRegisterRequest $request)
    {
        $data = $request->only('email', 'password','name');
        DB::beginTransaction();
        try {
            $result = $this->authService->register($data);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        return response()->success($result, Response::HTTP_OK);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $result = $this->authService->logout($user);
        return response()->success($result, Response::HTTP_OK);
    }
}
