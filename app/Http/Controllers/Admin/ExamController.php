<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExamCreateRequest;
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

    public function update(Request $request , $id)
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

    public function delete($id)
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

        return response()->success(new ExamDetail($res), Response::HTTP_OK, 'OK');
    }
}
