<?php

namespace App\Repositories;

use App\Models\Template;
use App\Models\TemplatePart;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class TemplateRepository
{
    private $template, $part;

    public function __construct(Template $template , TemplatePart $part)
    {
        $this->template = $template;
        $this->part = $part;
    }

    public function index($request, $offset, $limit)
    {
        $data = $this->template->with('parts');

        if ($request->search) {
            $data = $data->searchAttributes($data, $request->search);
        }
        $data = $data->skip($offset)->take($limit)->get();

        return $data;
    }

    public function show($id)
    {
        try {
            $template = $this->template->with('parts')->findOrFail($id);
        } catch (Throwable $e) {
            throw new ModelNotFoundException(__('exceptions.templateNotFound'));
        }

        return $template;
    }

    public function store($template, $parts)
    {
        $template_id = $this->template->create($template)->id;

        foreach ($parts as $part) {
            $temp = [];
            $temp['template_id'] = $template_id;
            $temp['name'] = $part['name'];
            $temp['order_in_test'] = $part['order_in_test'];
            $temp['total_questions'] = $part['total_questions'];
            $temp['has_group_question'] = $part['has_group_question'];
            $this->part->create($temp);
        }

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
