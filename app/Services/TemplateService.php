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
        $itemPerPage = array_key_exists('maxResultCount', $request) ? $request['maxResultCount'] : config('constant.USER_PER_PAGE');
        $offset = array_key_exists('skipCount', $request) ? $request['skipCount'] : 0;
        $sorting = array_key_exists('sorting', $request) ? explode(" ", $request['sorting']) : ['id', 'asc'];

        $data = $this->templateRepository->index($request, $offset, $itemPerPage, $sorting);

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
        $template = $this->templateRepository->updateTemplate($id, $request->input());
        $existed_parts = $template->parts->pluck('id')->toArray();
        foreach ($request['parts'] as $part) {
            $part['template_id'] = $id;
            $part['has_group_question'] = $part['has_group_question'] == "true" ? true : false;
            if ($part['id']) {
                $this->templateRepository->updatePart($part['id'], $part);
                $key = array_search($part['id'], $existed_parts);
                if ($key !== false) {
                    unset($existed_parts[$key]);
                }
            } else {
                $this->templateRepository->storePart($part);
            }
        }
        foreach ($existed_parts as $part) {
            $this->templateRepository->destroyPart($part);
        }
        return true;
    }

    public function destroy($id)
    {
        $res = $this->templateRepository->destroy($id);

        return $res;
    }

    public function getAllTemplates()
    {
        $res = $this->templateRepository->getAllTemplates();

        return $res;
    }
}
