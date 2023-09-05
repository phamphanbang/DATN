<?php

namespace App\Repositories;

use App\Models\TemplatePart;
use App\Models\Test;
use App\Models\ExamAnswer;
use App\Models\ExamGroup;
use App\Models\ExamPart;
use App\Models\ExamQuestion;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class ExamRepository
{
    public function store($request)
    {
        $temp = [];
        $temp['template_id'] = $request->template_id;
        $temp['name'] = $request->name;
        $temp['total_views'] = 0;
        $temp['status'] = config('enum.test_status.ACTIVE');
        $exam_id = Test::create($temp)->id;
        foreach ($request->parts as $part) {
            $this->storePart($exam_id, $part);
        }
        return true;
    }

    public function update($id, $request)
    {
        $test = Test::findOrFail($id);
        $test->template_id = $request->template_id;
        $test->name = $request->name;
        $test->status = $request->status;
        $test->update();
    }

    public function updatePart($exam_id, $request)
    {
        $part = ExamPart::findOrFail($request['id']);
        $part->order_in_test = $request['order_in_test'];
        $part->part_type = $request['part_type'];
        $part->total_questions = $request['total_question'];
        $part->has_group_question = $request['has_group_question'];
        $part->update();
    }

    public function storePart($exam_id, $part)
    {
        $temp = [];
        $temp['exam_id'] = $exam_id;
        $temp['order_in_test'] = $part['order_in_test'];
        $temp['part_type'] = $part['part_type'];
        $temp['total_questions'] = $part['total_question'];
        $temp['has_group_question'] = $part['has_group_question'];
        $part_id = ExamPart::create($temp)->id;
        if ($part['has_group_question']) {
            foreach ($part['groups'] as $group) {
                $this->storeGroupQuestion($exam_id, $part_id, $group);
            }
        } else {
            foreach ($part['questions'] as $question) {
                $this->storeQuestion($exam_id, $part_id, null, $question);
            }
        }
    }

    public function storeGroupQuestion($exam_id, $part_id, $group)
    {
        $temp = [];
        $temp['order_in_part'] = $group['order_in_part'];
        $temp['question'] = $group['question'];
        $fileName = null;
        if(request()->file($group['attchment'])) {
            $file = request()->file($group['attchment']);
            $extension = $file->getClientOriginalExtension();
            $fileName = 'test_' . $exam_id . 'part_' . $part_id . 'group_' . $group['order_in_part'] . '.' . $extension;
            $file->storeAs('tests/' . $exam_id, $fileName);
        }

        $temp['attachment'] = $fileName;
        $group_id = TestGroupQuestion::create($temp)->id;

        foreach ($group['questions'] as $question) {
            $this->storeQuestion($exam_id, $part_id, $group_id, $question);
        }
    }

    public function storeQuestion($exam_id, $part_id, $group_id = null, $question)
    {
        $question_temp = [];
        $question_temp['group_id'] = $group_id;
        $question_temp['part_id'] = $part_id;
        $question_temp['order_in_test'] = $question['order_in_test'];
        $question_temp['question'] = $question['question'];

        $fileName = null;
        if(request()->file($question['attchment'])) {
            $file = request()->file($question['attchment']);
            $extension = $file->getClientOriginalExtension();
            $fileName = 'test_' . $exam_id . '_part_' . $part_id . '_question_' . $question['order_in_test'] . '_attachment' . '.' . $extension;
            $file->storeAs('tests/' . $exam_id, $fileName);
        }
        $question_temp['attachment'] = $fileName;

        $fileName = null;
        if(request()->file($question['audio'])) {
            $file = request()->file($question['audio']);
            $extension = $file->getClientOriginalExtension();
            $fileName = 'test_' . $exam_id . '_part_' . $part_id . '_question_' . $question['audio'] . '_audio' . '.' . $extension;
            $file->storeAs('tests/' . $exam_id, $fileName);
        }
        $question_temp['audio'] = $fileName;

        $question_id = ExamQuestion::create($question_temp)->id;
        $question_temp['answer_id'] = 0;

        foreach ($question['answers'] as $answer) {
            $temp = [];
            $temp['question_id'] = $question_id;
            $temp['order_in_question'] = $answer['order_in_question'];
            $temp['answer'] = $answer['answer'];
            $result_id = ExamAnswer::create($temp)->id;
            if ($question['answer_id'] == $answer['order_in_question']) {
                $question_temp['answer_id'] = $result_id;
            }
        }

        ExamQuestion::findOrFail($question_id)->update($question_temp);
    }
}
