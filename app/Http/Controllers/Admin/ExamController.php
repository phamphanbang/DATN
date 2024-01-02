<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ExamCreateRequest;
use App\Http\Requests\Admin\ExamUpdateRequest;
use App\Http\Requests\Admin\QuestionUpdateRequest;
use App\Http\Requests\Admin\GroupUpdateRequest;
use App\Http\Resources\ExamDetail;
use App\Services\ExamService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Throwable;

class ExamController extends Controller
{

    public function __construct(
        protected ExamService $examService
    ) {
    }

    public function index(Request $request)
    {
        $data = $this->examService->index($request->all());

        return response()->list($data, Response::HTTP_OK);
    }

    public function store(ExamCreateRequest $request)
    {
        DB::beginTransaction();
        try {
            $res = $this->examService->storeExam($request);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        return response()->success($res, Response::HTTP_OK, 'OK');
    }

    public function update(ExamUpdateRequest $request , $id)
    {
        DB::beginTransaction();
        try {
            $res = $this->examService->updateExam($request , $id);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        return response()->success($res, Response::HTTP_OK, 'OK');
    }

    public function updateQuestion(QuestionUpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $res = $this->examService->updateQuestion($request , $id);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        return response()->success($res, Response::HTTP_OK, 'OK');
    }

    public function updateGroup(GroupUpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $res = $this->examService->updateGroup($request , $id);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        return response()->success($res, Response::HTTP_OK, 'OK');
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $res = $this->examService->deleteExam($id);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        return response()->success($res, Response::HTTP_OK, 'OK');
    }

    public function show($id)
    {
        $res = $this->examService->show($id);

        return response()->show(new ExamDetail($res));
    }
}
