<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserResetPasswordRequest;
use App\Http\Requests\User\UserInfoUpdateRequest;
use App\Services\UserService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Throwable;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function show($id)
    {
        $data = $this->userService->show($id);

        return response()->show($data);
    }

    public function update(UserInfoUpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $res = $this->userService->update($id, $request);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        return response()->show($res);
        // return response()->success($res, Response::HTTP_OK, __('user.update.success'));
    }

    public function resetPassword(UserResetPasswordRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $res = $this->userService->update($id, $request);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->success($res, Response::HTTP_OK, __('user.update.success'));
    }

}
