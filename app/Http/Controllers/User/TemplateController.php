<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TemplateService;
use Illuminate\Http\Response;

class TemplateController extends Controller
{
    private $templateService;

    public function __construct(TemplateService $templateService)
    {
        $this->templateService = $templateService;
    }

    public function getAllTemplates()
    {
        $data = $this->templateService->getAllTemplates();

        return response()->list($data, Response::HTTP_OK);
    }
}
