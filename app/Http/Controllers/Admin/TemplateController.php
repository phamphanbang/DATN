<?php

namespace App\Http\Controllers\Admin;

use App\Services\TemplateService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

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

    public function store(Request $request)
    {
        $res = $this->templateService->store($request);

        return response()->success(null, Response::HTTP_OK, __('template.create.success'));
    }

    public function destroy($id)
    {
        $res = $this->templateService->destroy($id);

        return response()->success(null, Response::HTTP_OK, __('template.delete.success'));
    }
}
