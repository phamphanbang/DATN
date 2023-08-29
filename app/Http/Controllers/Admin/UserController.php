<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $data = $this->userService->index($request);

        return response()->success($data, Response::HTTP_OK);
    }

    public function store(UserCreateRequest $request)
    {
        $res = $this->userService->store($request);

        return response()->success(null, Response::HTTP_OK, __('user.create.success'));
    }

    public function show($id)
    {
        $data = $this->userService->show($id);

        return response()->success($data, Response::HTTP_OK, __('user.show.success'));
    }

    public function update(UserUpdateRequest $request, $id)
    {
        $res = $this->userService->update($id, $request);

        return response()->success(null, Response::HTTP_OK, __('user.update.success'));
    }

    public function destroy($id)
    {
        $res = $this->userService->destroy($id);

        return response()->success(null, Response::HTTP_OK, __('user.delete.success'));
    }
}
