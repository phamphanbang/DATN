<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserCreateRequest;
use App\Http\Requests\Admin\UserResetPasswordRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
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

    public function index(Request $request)
    {
        $data = $this->userService->index($request->all());

        return response()->list($data, Response::HTTP_OK);
    }

    public function store(UserCreateRequest $request)
    {
        DB::beginTransaction();
        try {
            $res = $this->userService->store($request);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->success($res, Response::HTTP_OK, __('user.create.success'));
    }

    public function show($id)
    {
        $data = $this->userService->show($id);

        return response()->success($data, Response::HTTP_OK, __('user.show.success'));
    }

    public function update(UserUpdateRequest $request, $id)
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

    public function destroy($id)
    {
        $res = $this->userService->destroy($id);

        return response()->success(null, Response::HTTP_OK, __('user.delete.success'));
    }
}
