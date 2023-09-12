<?php

namespace App\Services;

use App\Repositories\ExamRepository;
use App\Repositories\TemplateRepository;

class ExamService
{
    public function __construct(
        protected ExamRepository $examRepository
    ) {
    }

    public function storeExam($request)
    {
        $exam_id = $this->examRepository->storeExam($request->input());
        $question_index = 1;
        foreach ($request['parts'] as $part) {
            $part['exam_id'] = $exam_id;
            $part_id = $this->examRepository->storePart($part);
            $num_of_answers = $part['num_of_answers'];
            if ($part['has_group_question']) {
                foreach ($part['groups'] as $group) {
                    $group['part_id'] = $part_id;
                    $group_id = $this->examRepository->storeGroup($group);
                    $num_of_question = $group['to_question'] - $group['from_question'];
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
                $num_of_question = $part['num_of_questions'];
                for ($i = 0; $i < $num_of_question; $i++) {
                    $question['part_id'] = $part_id;
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

    public function show($id)
    {
        $exam = $this->examRepository->show($id);
        return $exam;
    }
}
