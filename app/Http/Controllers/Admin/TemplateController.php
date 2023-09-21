<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TemplateUpdateRequest;
use App\Services\TemplateService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TemplateCreateRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Throwable;

class TemplateController extends Controller
{
    private $templateService;

    public function __construct(TemplateService $templateService)
    {
        $this->templateService = $templateService;
    }

    public function index(Request $request)
    {
        $data = $this->templateService->index($request);

        return response()->success($data, Response::HTTP_OK);
    }

    public function show($id)
    {
        $data = $this->templateService->show($id);

        return response()->success($data, Response::HTTP_OK, __('template.show.success'));
    }

    public function store(TemplateCreateRequest $request)
    {
        DB::beginTransaction();
        try {
            $res = $this->templateService->store($request);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        return response()->success($res, Response::HTTP_OK, __('template.create.success'));
    }

    public function update(TemplateUpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $res = $this->templateService->update($id, $request);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        return response()->success($res, Response::HTTP_OK, __('template.update.success'));
    }

    public function destroy($id)
    {
        $res = $this->templateService->destroy($id);

        return response()->success(null, Response::HTTP_OK, __('template.delete.success'));
    }
}
