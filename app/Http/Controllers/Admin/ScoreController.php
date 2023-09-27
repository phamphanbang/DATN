<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $data = $this->scoreService->index($request);

        return response()->success($data, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $res = $this->scoreService->store($request);
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

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $res = $this->scoreService->update($id, $request);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->success($res, Response::HTTP_OK, __('score.update.success'));
    }

    public function destroy($id)
    {
        $res = $this->scoreService->destroy($id);

        return response()->success(null, Response::HTTP_OK, __('score.delete.success'));
    }
}
