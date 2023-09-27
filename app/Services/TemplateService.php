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
        $template_id = $this->templateRepository->storeTemplate($request->input());
        foreach ($request['parts'] as $part) {
            $part['template_id'] = $template_id;
            $part_id = $this->templateRepository->storePart($part);
        }
        return $template_id;
    }

    public function update($id, $request)
    {
        $this->templateRepository->updateTemplate($id, $request->input());
        foreach($request['parts'] as $part) {
            $this->templateRepository->updatePart($part['id'],$part);
        }
        return true;
    }

    public function destroy($id)
    {
        $res = $this->templateRepository->destroy($id);

        return $res;
    }
}
