<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        $data = $request->only('email', 'password');
        $result = $this->authService->login($data);
        return response()->success($result, Response::HTTP_OK);
    }

    public function adminLogin(LoginRequest $request)
    {
        $data = $request->only('email', 'password');
        $result = $this->authService->adminLogin($data);
        return response()->success($result, Response::HTTP_OK);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $result = $this->authService->logout($user);
        return response()->success($result, Response::HTTP_OK);
    }
}
