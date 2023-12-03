<?php

namespace App\Services;

use App\Repositories\ExamRepository;
use App\Repositories\HistoryRepository;
use App\Repositories\ScoreRepository;
use App\Repositories\TemplateRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class ExamService
{
    public function __construct(
        protected ExamRepository $examRepository,
        protected TemplateRepository $templateRepository,
        protected HistoryRepository $historyRepository,
        protected ScoreRepository $scoreRepository
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
        $folder = 'exams/' . $id;
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
        return $exam;
    }

    public function updateQuestion($request, $id)
    {
        $data['id'] = $id;
        $data['question'] = $request['question'];
        $data['audio'] = $request['audio'];
        $data['attachment'] = $request['attachment'];
        $keys = ['audio', 'attachment'];
        $question = $this->examRepository->showQuestion($id);

        $folder = 'exams/' . $request['exam_id'];

        foreach ($keys as $key) {
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
        foreach ($request['answers'] as $answer) {
            $res = $this->examRepository->updateAnswer($answer);
        }
        return $res;
    }

    public function updateGroup($request, $id)
    {
        $data['id'] = $id;
        $data['question'] = $request['question'];
        $keys = ['audio', 'attachment'];
        $folder = 'exams/' . $request['exam_id'];
        $group = $this->examRepository->showGroup($id);
        $data['audio'] = $group['audio'];
        $data['attachment'] = $group['attachment'];
        foreach ($keys as $key) {
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $extension = $file->getClientOriginalExtension();
                $fileName = $id . '_' . $key . '.' . $extension;
                $file->storeAs($folder, $fileName);
                $data[$key] = $fileName;
            } elseif ($group[$key] !== $request[$key]) {
                if (Storage::exists($folder . $group[$key])) {
                    Storage::delete($folder . $group[$key]);
                }
                $data[$key] = '';
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

    public function getExamDetail($id)
    {
        $exam = $this->examRepository->getExamDetail($id);
        $data['id'] = $exam['id'];
        $data['name'] = $exam['name'];
        $data['template_id'] = $exam['template_id'];
        $data['total_views'] = $exam['total_views'];
        $data['template_name'] = $exam['template']['name'];
        $data['duration'] = $exam['template']['duration'];
        $data['total_parts'] = $exam['template']['total_parts'];
        $data['total_questions'] = $exam['template']['total_questions'];
        $data['comments_count'] = $exam['comments_count'];

        foreach ($exam['parts'] as $part) {
            $p['id'] = $part['id'];
            $p['order_in_test'] = $part['order_in_test'];
            $p['num_of_questions'] = $part['template']['num_of_questions'];
            $p['num_of_answers'] = $part['template']['num_of_answers'];
            $p['part_type'] = $part['template']['part_type'];
            $p['has_group_question'] = $part['template']['has_group_question'];
            $data['parts'][] = $p;
        }
        return $data;
    }

    public function getExamForTest($id, $request)
    {
        $exam = $this->examRepository->getExamForTest($id, $request);
        if ($exam['template']['status'] !== 'active') {
            throw new ModelNotFoundException('Bài thi này không tồn tại');
        }
        return $exam;
    }

    public function submit($request, $id)
    {
        $history['user_id'] = Auth::user()->id;
        $history['exam_id'] = $id;
        $history['duration'] = $request['duration'];
        $history['test_type'] = $request['test_type'];
        $history = $this->historyRepository->storeHistory($history);
        $total_questions = 0;
        $right_questions = 0;
        $wrong_questions = 0;
        $null_questions = 0;
        $reading_questions = 0;
        $listening_questions = 0;
        foreach ($request['parts'] as $part) {
            $historyPart['history_id'] = $history->id;
            $historyPart['part_id'] = $part['part_id'];
            $part_type = $part['part_type'];
            // dd($historyPart);
            $createPart = $this->historyRepository->storeHistoryPart($historyPart);
            foreach ($part['answers'] as $answer) {
                $total_questions++;
                $historyAnswer['question_id'] = $answer['question_id'];
                $historyAnswer['part_id'] = $createPart->id;
                $historyAnswer['answer_id'] = $answer['answer_id'];
                $historyAnswer['is_right'] = $answer['is_right'];
                if (!$answer['answer_id']) {
                    $null_questions++;
                }
                if ($answer['is_right'] == "true") {
                    $right_questions++;
                    if ($part_type === 'reading') {
                        $reading_questions++;
                    } else {
                        $listening_questions++;
                    }
                }
                // dd($historyAnswer);
                $this->historyRepository->storeHistoryAnswer($historyAnswer);
            }
        }
        if($request['test_type'] == 'fulltest') {
            $reading_score = $this->scoreRepository->getScore($reading_questions, 'reading');
            $listening_score = $this->scoreRepository->getScore($listening_questions, 'listening');
            $history->score = $reading_score['score'] + $listening_score['score'];
        }

        $wrong_questions = $total_questions - $right_questions - $null_questions;
        $history->total_questions = $total_questions;
        $history->right_questions = $right_questions;
        $history->wrong_questions = $wrong_questions;
        $history->save();
        $data['history_id'] = $history->id;
        return $data;
    }

    public function getHistoryDetail($exam_id,$history_id)
    {
        $history = $this->historyRepository->show($history_id);
        $history_part = [];
        foreach($history['parts'] as $p) {
            $history_part[] = $p['part_id'];
        }
        $request['parts'] = $history_part;
        $exam = $this->examRepository->getExamForTest($exam_id,$request);

        $data['name'] = $exam['name'];
        $data['history_id'] = $history_id;
        $data['exam_id'] = $exam_id;
        $data['total_questions'] = $history['total_questions'];
        $data['right_questions'] = $history['right_questions'];
        $data['wrong_questions'] = $history['wrong_questions'];
        $data['score'] = $history['score'];
        $data['test_type'] = $history['test_type'];
        $data['parts'] = $this->renderPart($exam['parts'],$history['parts']);

        return $data;
    }

    public function renderPart($parts,$history_part)
    {
        $partsArray = [];
        $part_collection = collect($history_part);
        // dd($history_part);
        foreach($parts as $index => $part) {
            $answer_part = $part_collection->first(function ($a) use ($part) {
                return $a['part_id'] === $part['id'];
            });
            $answers = $answer_part->answers;
            $template_part = $part->template;
            $data['id'] = $part['id'];
            $data['order_in_test'] = $part['order_in_test'];
            $data['part_type'] = $template_part['part_type'];
            $data['has_group_question'] = $template_part['has_group_question'] == 1 ? true : false;
            if($template_part['has_group_question'] == 1) {
                $data['groups'] = $this->renderGroup($part->groups,$answers);
            } else {
                $data['questions'] = $this->renderQuestion($part->questions,$answers);
            }
            $partsArray[] = $data;
            $data = [];
        }
        return $partsArray;
    }

    public function renderGroup($groups,$answers)
    {
        $groupsArray = [];
        foreach($groups as $group) {

            $data['id'] = $group['id'];
            $data['part_id'] = $group['part_id'];
            $data['question'] = $group['question'];
            $data['order_in_part'] = $group['order_in_part'];
            $data['from_question'] = $group['from_question'];
            $data['to_question'] = $group['to_question'];
            $data['attachment'] = $group['attachment'];
            $data['audio'] = $group['audio'];
            $data['questions'] = $this->renderQuestion($group->questions,$answers);
            $groupsArray[] = $data;
            $data = [];
        }
        return $groupsArray;
    }

    public function renderQuestion($questions,$answers)
    {
        $questionsArray = [];
        $answer_collection = collect($answers);
        foreach($questions as $question) {
            // dd($answer_collection,$question);
            $answer = $answer_collection->first(function ($a) use ($question) {
                return $a['question_id'] == $question['id'];
            });
            $data['id'] = $question['id'];
            $data['part_id'] = $question['part_id'];
            $data['group_id'] = $question['group_id'];
            $data['question'] = $question['question'];
            $data['audio'] = $question['audio'];
            $data['attachment'] = $question['attachment'];
            $data['order_in_test'] = $question['order_in_test'];

            $data['select_answer'] = $answer['answer_id'];
            $data['is_right'] = $answer['is_right'];

            $data['answers'] = $this->renderAnswer($question->answers);
            $questionsArray[] = $data;
            $data = [];
        }
        return $questionsArray;
    }

    public function renderAnswer($answers)
    {
        $answersArray = [];
        foreach($answers as $answer) {
            $data['id'] = $answer['id'];
            $data['question_id'] = $answer['question_id'];
            $data['order_in_question'] = $answer['order_in_question'];
            $data['answer'] = $answer['answer'];
            $data['is_right'] = $answer['is_right'];
            $answersArray[] = $data;
            $data = [];
        }
        return $answersArray;
    }
}
