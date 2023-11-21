<?php

namespace App\Repositories;

use App\Models\Template;
use App\Models\TemplatePart;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class TemplateRepository
{
    private $template, $part;

    public function __construct(Template $template, TemplatePart $part)
    {
        $this->template = $template;
        $this->part = $part;
    }

    public function index($request, $offset, $limit)
    {
        $query = $this->template;
        if (array_key_exists('search',$request) && $request['search']) {
            $query = $query->searchAttributes($query, $request['search']);
        }
        $query = $query->with(['parts'])->withCount('exams');
        $data['totalCount'] = $query->count();
        $data['items'] = $query->orderBy('created_at', 'DESC')->skip($offset)->take($limit)->get();

        return $data;
    }

    public function getAllTemplates()
    {
        $query = $this->template;
        $data['totalCount'] = $query->count();
        $data['items'] = $query->get(['id','name'])->toArray();
        return $data;
    }

    public function show($id)
    {
        try {
            $template = $this->template->with(['parts'])->withCount('exams')->findOrFail($id);
        } catch (Throwable $e) {
            throw new ModelNotFoundException(__('exceptions.templateNotFound'));
        }

        return $template;
    }

    public function getPartById($id)
    {
        try {
            $part = $this->part->findOrFail($id);
        } catch (Throwable $e) {
            throw new ModelNotFoundException(__('exceptions.templatePartNotFound'));
        }
        return $part;
    }

    public function storeTemplate($template)
    {
        $template_id = $this->template->create($template)->id;
        return $template_id;
    }

    public function storePart($part)
    {
        $part["has_group_question"] = "true" ? true : false;
        $part_id = $this->part->create($part)->id;
        return $part_id;
    }

    public function updateTemplate($id, $data)
    {
        try {
            $template = $this->template->findOrFail($id);
        } catch (Throwable $e) {
            throw new ModelNotFoundException(__('exceptions.templateNotFound'));
        }
        $template->update($data);
        $template->exams()->delete();
        return true;
    }

    public function updatePart($id, $data)
    {
        try {
            $part = $this->part->findOrFail($id);
        } catch (Throwable $e) {
            throw new ModelNotFoundException(__('exceptions.templateNotFound'));
        }
        $part->update($data);
        return true;
    }

    public function destroy($id)
    {
        try {
            $template = $this->template->findOrFail($id);
        } catch (Throwable $e) {
            throw new ModelNotFoundException(__('exceptions.templateNotFound'));
        }

        return $template->delete();
    }

    public function countAllTemplate()
    {
        return $this->template->all()->count();
    }
}
