<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ScoreCreateRequest;
use App\Http\Requests\Admin\ScoreUpdateRequest;
use App\Services\ScoreService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Throwable;

class ScoreController extends Controller
{
    public function __construct(
        protected ScoreService $scoreService
    ) {
    }

    public function index(Request $request)
    {
        $data = $this->scoreService->index();

        return response()->list($data, Response::HTTP_OK);
    }

    public function store(ScoreCreateRequest $request)
    {
        DB::beginTransaction();
        try {
            $res = $this->scoreService->store($request->all());
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->success($res, Response::HTTP_OK, __('score.create.success'));
    }

    public function show($id)
    {
        $data = $this->scoreService->show($id);

        return response()->success($data, Response::HTTP_OK, __('score.show.success'));
    }

    public function update(ScoreUpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $res = $this->scoreService->update($id, $request->all());
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->success($res, Response::HTTP_OK, __('score.update.success'));
    }

    public function destroy(Request $request)
    {
        $res = $this->scoreService->destroy($request->all());

        return response()->success(null, Response::HTTP_OK, __('score.delete.success'));
    }
}
