<?php

namespace App\Repositories;

use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\ExamGroup;
use App\Models\ExamPart;
use App\Models\ExamQuestion;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;
use Illuminate\Support\Facades\Storage;

class ExamRepository
{

    public function __construct(
        protected Exam $exam,
        protected ExamPart $part,
        protected ExamGroup $group,
        protected ExamQuestion $question,
        protected ExamAnswer $answer
    ) {
    }

    public function index($request, $offset, $limit)
    {
        $data = $this->exam->with(['template']);

        if ($request->search) {
            $data = $data->searchAttributes($data, $request->search);
        }

        $data = $data->skip($offset)->take($limit)->get();

        return $data;
    }

    public function storeExam($exam)
    {
        $data['name'] = $exam['name'];
        $data['template_id'] = $exam['template_id'];
        $data['total_views'] = 0;
        $data['status'] = config('enum.exam_status.DRAFT');
        $exam_id = $this->exam->create($data)->id;
        return $exam_id;
    }

    public function storePart($part)
    {
        $part_id = $this->part->create($part)->id;
        return $part_id;
    }

    public function storeGroup($group)
    {
        $group_id = $this->group->create($group)->id;
        return $group_id;
    }

    public function storeQuestion($question)
    {
        $question_id = $this->question->create($question)->id;
        return $question_id;
    }

    public function storeAnswer($question_id, $order_in_question)
    {
        $default_answer = 'A.';
        switch ($order_in_question) {
            case 1:
                $default_answer = 'A.';
                break;
            case 2:
                $default_answer = 'B.';
                break;
            case 3:
                $default_answer = 'C.';
                break;
            case 4:
                $default_answer = 'D.';
                break;
        }
        $answer['question_id'] = $question_id;
        $answer['order_in_question'] = $order_in_question;
        $answer['answer'] = $default_answer;
        $answer['is_right'] = $order_in_question == 1 ? config('enum.answer_status.RIGHT') : config('enum.answer_status.WRONG');

        $answer_id = $this->answer->create($answer);
        return $answer_id;
    }

    public function updateExam($id, $data)
    {
        try {
            $exam = $this->exam->findOrFail($id);
        } catch (Throwable $e) {
            throw new ModelNotFoundException('Không tìm thấy bài thi với id ' . $id);
        }
        $exam->name = $data['name'];
        $exam->status = $data['status'];
        $exam->save();
        return true;
    }

    public function updatePart($id, $data)
    {
        try {
            $part = $this->part->findOrFail($id);
        } catch (Throwable $e) {
            throw new ModelNotFoundException('Không tìm thấy phần thi với id ' . $id);
        }
        return $part;
    }

    public function updateGroup($id, $part, $exam_id, $data)
    {
        try {
            $group = $this->group->findOrFail($id);
        } catch (Throwable $e) {
            throw new ModelNotFoundException('Không tìm thấy nhóm câu hỏi với id ' . $id);
        }

        $defaultName = 'part-' . $part->order_in_test . '-group-' . $group->order_in_part;

        $audioFileName = $defaultName . '_audio';
        $attachmentFileName = $defaultName . '_attachment';

        $group->attachment = $this->fileHandler($group, $attachmentFileName, $data, $exam_id, 'attachment');
        $group->audio = $this->fileHandler($group, $audioFileName, $data, $exam_id, 'audio');

        $group->question = $data['question'];

        $group->save();
        return true;
    }

    public function updateQuestion($id, $part, $exam_id, $data)
    {
        try {
            $question = $this->question->findOrFail($id);
        } catch (Throwable $e) {
            throw new ModelNotFoundException('Không tìm thấy câu hỏi với id ' . $id);
        }

        $defaultName = 'part-' . $part->order_in_test . '-question-' . $question->order_in_test;

        $audioFileName = $defaultName . '_audio';
        $attachmentFileName = $defaultName . '_attachment';

        $question->attachment = $this->fileHandler($question, $attachmentFileName, $data, $exam_id, 'attachment');
        $question->audio = $this->fileHandler($question, $audioFileName, $data, $exam_id, 'audio');

        $question->question = $data['question'];
        $question->question_type_id = $data['question_type_id'];

        $question->save();
        return true;
    }

    public function updateAnswer($id, $data)
    {
        try {
            $answer = $this->answer->findOrFail($id);
        } catch (Throwable $e) {
            throw new ModelNotFoundException('Không tìm thấy đáp án với id ' . $id);
        }
        $answer->answer = $data['answer'];
        $answer->status = $data['status'];
        $answer->save();
        return true;
    }

    public function show($id)
    {
        try {
            $exam = $this->exam->with([
                // 'parts',
                // 'parts.groups',
                // 'parts.groups.questions',
                // 'parts.groups.questions.answers',
                // 'parts.questions',
                // 'parts.questions.answers'
            ])->findOrFail($id);
        } catch (Throwable $e) {
            throw new ModelNotFoundException('Không tìm thấy bài thi với id ' . $id);
        }
        return $exam;
    }

    public function deleteExam($id)
    {
        try {
            $exam = $this->exam->findOrFail($id);
        } catch (Throwable $e) {
            throw new ModelNotFoundException('Không tìm thấy bài thi với id ' . $id);
        }
        $exam->delete();
        if (Storage::exists('exams/' . $id)) {
            Storage::deleteDirectory('exams/' . $id);
        }
        return true;
    }

    public function fileHandler($model, $fileName, $data, $exam_id, $type)
    {
        $res = null;
        if ($data[$type] == null && $model[$type] != null) {
            $res = $this->removeFile($exam_id, $model[$type]);
        }
        if (request()->file($fileName)) {
            $res = $this->saveFile($exam_id, $fileName);
        }
        return $res;
    }

    public function saveFile($exam_id, $saveFile)
    {
        $file = request()->file($saveFile);
        $extension = $file->getClientOriginalExtension();
        $fileName = 'exam_' . $exam_id . '_' . $saveFile . $extension;
        $file->storeAs('exams/' . $exam_id, $fileName);
        return $fileName;
    }

    public function removeFile($exam_id, $fileName)
    {
        $linkToFile = 'exams/' . $exam_id . '/' . $fileName;
        if (Storage::exists($linkToFile)) {
            Storage::delete($linkToFile);
        }
        return null;
    }
}
