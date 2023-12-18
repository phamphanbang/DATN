<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\CommentService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Throwable;

class CommentController extends Controller
{

    public function __construct(protected CommentService $commentService)
    {
    }

    public function index(Request $request, $id, $type)
    {
        $data = $this->commentService->index($request->all(), $id, $type);

        return response()->list($data, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $res = $this->commentService->store($request);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->success($res, Response::HTTP_OK, __('user.create.success'));
    }

    public function show($id)
    {
        $data = $this->commentService->show($id);

        return response()->success($data, Response::HTTP_OK, __('user.show.success'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $res = $this->commentService->update($id, $request);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        return response()->show($res);
    }


    public function destroy($id)
    {
        $res = $this->commentService->destroy($id);

        return response()->success(null, Response::HTTP_OK, __('user.delete.success'));
    }
}
