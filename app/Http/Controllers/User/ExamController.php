<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExamFortest;
use Illuminate\Http\Request;
use App\Services\ExamService;
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
        $request = $request->all();
        $request['status'] = 'active';
        $data = $this->examService->index($request);

        return response()->list($data, Response::HTTP_OK);
    }

    public function getExamDetail($id)
    {
        $data = $this->examService->getExamDetail($id);

        return response()->show($data);
    }

    public function getExamFortest(Request $request,$id)
    {
        $data = $this->examService->getExamForTest($id,$request->all());

        return response()->show(new ExamFortest($data));
    }

    public function submit(Request $request,$id)
    {
        DB::beginTransaction();
        try {
            $data = $this->examService->submit($request,$id);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        return response()->show($data);
    }

    public function getHistory($exam_id,$history_id)
    {
        DB::beginTransaction();
        try {
            $data = $this->examService->getHistoryDetail($exam_id,$history_id);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        return response()->show($data);
    }
}
