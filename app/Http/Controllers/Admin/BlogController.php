<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogCreateRequest;
use App\Http\Requests\BlogUpdateRequest;
use App\Services\BlogService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Throwable;

class BlogController extends Controller
{
    public function __construct(
        protected BlogService $blogService
    ) {
    }

    public function index(Request $request)
    {
        $data = $this->blogService->index($request->all());

        return response()->list($data, Response::HTTP_OK);
    }

    public function store(BlogCreateRequest $request)
    {
        DB::beginTransaction();
        try {
            $res = $this->blogService->store($request->all());
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->success($res, Response::HTTP_OK, __('blog.create.success'));
    }

    public function show($id)
    {
        $data = $this->blogService->show($id);

        return response()->success($data, Response::HTTP_OK, __('blog.show.success'));
    }

    public function update(BlogUpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $res = $this->blogService->update($id, $request->all());
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->success($res, Response::HTTP_OK, __('blog.update.success'));
    }

    public function destroy($id)
    {
        $res = $this->blogService->destroy($id);

        return response()->success(null, Response::HTTP_OK, __('blog.delete.success'));
    }
}
