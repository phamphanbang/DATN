<?php

namespace App\Services;

use App\Repositories\TemplateRepository;

class TemplateService
{
    private $templateRepository;
    public function __construct(TemplateRepository $templateRepository)
    {
        $this->templateRepository = $templateRepository;
    }

    public function index($request)
    {
        $page = $request->page ? $request->page : config('constant.DEFAULT_PAGE');
        $totalUser = $this->templateRepository->countAllTemplate();
        $itemPerPage = config('constant.USER_PER_PAGE');
        $totalPage = ceil($totalUser / $itemPerPage);
        $checkPage = $page > $totalPage ? $totalPage : $page;
        $offset = ($checkPage - 1) * $itemPerPage;

        $data = $this->templateRepository->index($request, $offset, $itemPerPage);

        return $data;
    }

    public function show($id)
    {
        $data = $this->templateRepository->show($id);

        return $data;
    }

    public function store($request)
    {
        $template = [];
        $parts = $request->parts;

        $template['name'] = $request->name;
        $template['duration'] = $request->duration;
        $template['description'] = $request->description;
        $template['total_parts'] = $request->total_parts;
        $template['total_questions'] = $request->total_questions;
        $template['total_score'] = $request->total_score;
        $template['status'] = $request->status;

        return $this->templateRepository->store($template, $parts);
    }

    public function update($id, $request)
    {
        $template = [];
        $template['name'] = $request->name;
        $template['duration'] = $request->duration;
        $template['description'] = $request->description;
        $template['total_parts'] = $request->total_parts;
        $template['total_questions'] = $request->total_questions;
        $template['total_score'] = $request->total_score;
        $template['status'] = $request->status;

        return $this->templateRepository->update($id, $template, $request);
    }

    public function destroy($id)
    {
        $res = $this->templateRepository->destroy($id);

        return $res;
    }
}
