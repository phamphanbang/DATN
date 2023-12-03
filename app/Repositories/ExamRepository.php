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

    public function index($request, $offset, $limit, $sorting)
    {
        $query = $this->exam;
        if (array_key_exists('search', $request) && $request['search']) {
            $query = $query->searchAttributes($query, $request['search']);
        }
        if (array_key_exists('template_id', $request) && $request['template_id']) {
            $query = $query->where('template_id', $request['template_id']);
        }
        if (array_key_exists('status', $request) && $request['status']) {
            $query = $query->where('status', $request['status']);
        }
        $query = $query->orderBy($sorting[0], $sorting[1]);
        $query = $query->with(['template'])->withCount('comments');
        $data['totalCount'] = $query->count();
        $data['items'] = $query->orderBy('created_at', 'DESC')->skip($offset)->take($limit)->get();

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
        $exam->audio = $data['audio'];
        $exam->save();
        return $exam;
    }

    public function getPart($id)
    {
        try {
            $part = $this->part->findOrFail($id);
        } catch (Throwable $e) {
            throw new ModelNotFoundException('Không tìm thấy phần thi với id ' . $id);
        }
        return $part;
    }

    public function getAnswer($id)
    {
        try {
            $answer = $this->answer->findOrFail($id);
        } catch (Throwable $e) {
            throw new ModelNotFoundException('Không tìm thấy câu trả lời với id ' . $id);
        }
        return $answer;
    }

    public function getAnswers($answer_ids)
    {
        $answer = $this->answer->whereIn('id',$answer_ids)->get();
        return $answer;
    }

    public function updateGroup($data)
    {
        try {
            $group = $this->group->findOrFail($data['id']);
        } catch (Throwable $e) {
            throw new ModelNotFoundException('Không tìm thấy nhóm câu hỏi với id ' . $data['id']);
        }
        $group->audio = $data['audio'];
        $group->attachment = $data['attachment'];
        $group->question = $data['question'];

        $group->save();
        return true;
    }

    public function updateQuestion($data)
    {
        try {
            $question = $this->question->findOrFail($data['id']);
            $part = $question->part;
            $exam_id = $part->test->id;
        } catch (Throwable $e) {
            throw new ModelNotFoundException('Không tìm thấy câu hỏi với id ' . $data['id']);
        }

        $question->audio = $data['audio'];
        $question->attachment = $data['attachment'];

        $question->question = $data['question'];

        $question->save();
        return true;
    }

    public function updateAnswer($data)
    {
        try {
            $answer = $this->answer->findOrFail($data['id']);
        } catch (Throwable $e) {
            throw new ModelNotFoundException('Không tìm thấy đáp án với id ' . $data['id']);
        }
        $answer->answer = $data['answer'];
        $answer->is_right = $data['is_right'] == "true" ? 1 : 0;
        $answer->save();
        return true;
    }

    public function show($id)
    {
        try {
            $exam = $this->exam->findOrFail($id);
        } catch (Throwable $e) {
            throw new ModelNotFoundException('Can not find exam with id ' . $id);
        }
        return $exam;
    }

    public function showQuestion($id)
    {
        try {
            $question = $this->question->findOrFail($id);
        } catch (Throwable $e) {
            throw new ModelNotFoundException('Can not find exam with id ' . $id);
        }
        return $question;
    }

    public function showGroup($id)
    {
        try {
            $group = $this->group->findOrFail($id);
        } catch (Throwable $e) {
            throw new ModelNotFoundException('Can not find exam with id ' . $id);
        }
        return $group;
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

    // public function fileHandler($model, $fileName, $data, $exam_id, $type)
    // {
    //     $res = null;
    //     if ($data[$type] == null && $model[$type] != null) {
    //         $res = $this->removeFile($exam_id, $model[$type]);
    //     }
    //     if (request()->file($fileName)) {
    //         $res = $this->saveFile($exam_id, $fileName);
    //     }
    //     return $res;
    // }

    // public function saveFile($exam_id, $saveFile)
    // {
    //     $file = request()->file($saveFile);
    //     $extension = $file->getClientOriginalExtension();
    //     $fileName = 'exam_' . $exam_id . '_' . $saveFile . $extension;
    //     $file->storeAs('exams/' . $exam_id, $fileName);
    //     return $fileName;
    // }

    // public function removeFile($exam_id, $fileName)
    // {
    //     $linkToFile = 'exams/' . $exam_id . '/' . $fileName;
    //     if (Storage::exists($linkToFile)) {
    //         Storage::delete($linkToFile);
    //     }
    //     return null;
    // }

    public function getExamForHomePage()
    {
        $query = $this->exam;
        $limit = 8;
        $query = $query->with(['template']);
        $data = $query->orderBy('created_at', 'DESC')->take($limit)->get();

        return $data;
    }

    public function getExamDetail($id)
    {
        try {
            $exam = $this->exam
                ->with(['template', 'parts', 'parts.template'])
                ->withCount('comments')
                ->where('status', 'active')->findOrFail($id);
        } catch (Throwable $e) {
            throw new ModelNotFoundException('Bài thi này không tồn tại');
        }

        return $exam;
    }

    public function getExamForTest($id, $request)
    {
        $query = $this->exam;
        if (array_key_exists('part', $request) && $request['part']) {
            $query = $query->with(['parts' => function ($q) use ($request) {
                $q->whereIn('order_in_test', [...$request['part']])->orderBy('order_in_test', 'asc');
            }]);
        } else {
            $query = $query->with('parts');
        }
        $query = $query->with('template');
        try {
            $exam = $query->where('id', $id)->where('status', 'active')->firstOrFail();
        } catch (Throwable $e) {
            throw new ModelNotFoundException('Bài thi này không tồn tại');
        }
        return $exam;
    }
}
