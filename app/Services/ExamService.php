<?php

namespace App\Services;

use App\Repositories\ExamRepository;
use App\Repositories\TemplateRepository;
use Illuminate\Support\Facades\Storage;

class ExamService
{
    public function __construct(
        protected ExamRepository $examRepository,
        protected TemplateRepository $templateRepository
    ) {
    }

    public function index($request)
    {
        $itemPerPage = array_key_exists('maxResultCount', $request) ? $request['maxResultCount'] : config('constant.USER_PER_PAGE');
        $offset = array_key_exists('skipCount', $request) ? $request['skipCount'] : 0;
        $sorting = array_key_exists('sorting', $request) ? explode(" ", $request['sorting']) : ['id', 'asc'];

        $data = $this->examRepository->index($request, $offset, $itemPerPage, $sorting);

        return $data;
    }

    public function storeExam($request)
    {
        $exam_id = $this->examRepository->storeExam($request->input());
        $question_index = 1;
        foreach ($request['parts'] as $part) {
            $part['exam_id'] = $exam_id;
            $part_id = $this->examRepository->storePart($part);
            $part_info = $this->templateRepository->getPartById($part['template_part_id']);
            $num_of_answers = $part_info['num_of_answers'];
            if ($part_info['has_group_question']) {
                foreach ($part['groups'] as $group) {
                    $group['part_id'] = $part_id;
                    $group['from_question'] = $question_index;
                    $group['to_question'] = $question_index + $group['num_of_questions'] - 1;
                    $group_id = $this->examRepository->storeGroup($group);
                    $num_of_question = $group['num_of_questions'];
                    for ($i = 0; $i < $num_of_question; $i++) {
                        $question['part_id'] = $part_id;
                        $question['group_id'] = $group_id;
                        $question['order_in_test'] = $question_index;
                        $question_id = $this->examRepository->storeQuestion($question);
                        for ($j = 1; $j <= $num_of_answers; $j++) {
                            $answer_id = $this->examRepository->storeAnswer($question_id, $j);
                        }
                        $question_index++;
                    }
                }
            } else {
                $num_of_question = $part_info['num_of_questions'];
                for ($i = 0; $i < $num_of_question; $i++) {
                    $question['part_id'] = $part_id;
                    $question['group_id'] = null;
                    $question['order_in_test'] = $question_index;
                    $question_id = $this->examRepository->storeQuestion($question);
                    for ($j = 1; $j <= $num_of_answers; $j++) {
                        $answer_id = $this->examRepository->storeAnswer($question_id, $j);
                    }
                    $question_index++;
                }
            }
        }
        return $exam_id;
    }

    public function updateExam($request, $id)
    {
        // $exam = $this->examRepository->updateExam($id, $request);
        $exam = $this->examRepository->show($id);
        $data['name'] = $request['name'];
        $data['status'] = $request['status'];
        $data['audio'] = $request['audio'];
        $key = 'audio';
        $folder = 'exams/'.$id;
        if ($request->hasFile($key)) {
            $file = $request->file($key);
            $extension = $file->getClientOriginalExtension();
            $fileName = $id . '_' . $key . '.' . $extension;
            $file->storeAs($folder, $fileName);
            $data[$key] = $fileName;
        } elseif ($exam[$key] !== $data[$key]) {
            if (Storage::exists($folder . $exam[$key])) {
                Storage::delete($folder . $exam[$key]);
                $data[$key] = '';
            }
        }
        $exam = $this->examRepository->updateExam($id, $data);
        // foreach ($request['parts'] as $requestPart) {
        //     $part = $this->examRepository->getPart($requestPart['id']);
        //     if (array_key_exists('groups', $requestPart)) {
        //         foreach ($requestPart['groups'] as $requestGroup) {
        //             $this->examRepository->updateGroup(
        //                 $part,
        //                 $exam->id,
        //                 $requestGroup
        //             );
        //             foreach ($requestGroup['questions'] as $requestQuestion) {
        //                 $this->examRepository->updateQuestion($part, $exam->id, $requestQuestion);
        //                 foreach ($requestQuestion['answers'] as $requestAnswer) {
        //                     $this->examRepository->updateAnswer($requestAnswer);
        //                 }
        //             }
        //         }
        //     } else {
        //         foreach ($requestPart['questions'] as $requestQuestion) {
        //             $this->examRepository->updateQuestion($part, $exam->id, $requestQuestion);
        //             foreach ($requestQuestion['answers'] as $requestAnswer) {
        //                 $this->examRepository->updateAnswer($requestAnswer);
        //             }
        //         }
        //     }
        // }

        return $exam;
    }

    public function updateQuestion($request , $id)
    {
        $data['id'] = $id;
        $data['question'] = $request['question'];
        $data['audio'] = $request['audio'];
        $data['attachment'] = $request['attachment'];
        $keys = ['audio','attachment'];
        $question = $this->examRepository->showQuestion($id);

        $folder = 'exams/'.$request['exam_id'];

        foreach($keys as $key) {
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $extension = $file->getClientOriginalExtension();
                $fileName = 'question_' . $id . '_' . $key . '.' . $extension;
                $file->storeAs($folder, $fileName);
                $data[$key] = $fileName;
            } elseif ($question[$key] !== $data[$key]) {
                if (Storage::exists($folder . $question[$key])) {
                    Storage::delete($folder . $question[$key]);
                    $data[$key] = '';
                }
            }
        }
        $res = $this->examRepository->updateQuestion($data);
        foreach($request['answers'] as $answer) {
            $res = $this->examRepository->updateAnswer($answer);
        }
        return $res;
    }

    public function updateGroup($request , $id)
    {
        $data['id'] = $id;
        $data['question'] = $request['question'];
        $keys = ['audio','attachment'];
        $folder = 'exams/'.$request['exam_id'];
        $group = $this->examRepository->showGroup($id);
        foreach($keys as $key) {
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $extension = $file->getClientOriginalExtension();
                $fileName = $id . '_' . $key . '.' . $extension;
                $file->storeAs($folder, $fileName);
                $data[$key] = $fileName;
            } elseif ($group[$key] !== $data[$key]) {
                if (Storage::exists($folder . $group[$key])) {
                    Storage::delete($folder . $group[$key]);
                    $data[$key] = '';
                }
            }
        }

        $res = $this->examRepository->updateGroup($data);
        return $res;
    }

    public function deleteExam($id)
    {
        $res = $this->examRepository->deleteExam($id);

        return $res;
    }

    public function show($id)
    {
        $exam = $this->examRepository->show($id);
        return $exam;
    }
}
