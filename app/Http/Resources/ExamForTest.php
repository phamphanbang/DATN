<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamFortest extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $duration = $this['template']['duration'];
        if (array_key_exists('duration', $request->all()) && $request['duartion']) {
            $duration = $request['duartion'];
        }
        return [
            "id" => $this->id,
            "duration" => $duration,
            "name" => $this->name,
            "audio" => $this->audio,
            "parts" => $this->renderPart($this['parts'])
        ];
    }

    public function renderPart($parts)
    {
        $partsArray = [];
        foreach($parts as $part) {
            $template_part = $part->template;
            $data['id'] = $part['id'];
            $data['order_in_test'] = $part['order_in_test'];
            $data['part_type'] = $template_part['part_type'];
            $data['has_group_question'] = $template_part['has_group_question'] == 1 ? true : false;
            if($template_part['has_group_question'] == 1) {
                $data['groups'] = $this->renderGroup($part->groups);
            } else {
                $data['questions'] = $this->renderQuestion($part->questions);
            }
            $partsArray[] = $data;
            $data = [];
        }
        return $partsArray;
    }

    public function renderGroup($groups)
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
            $data['questions'] = $this->renderQuestion($group->questions);
            $groupsArray[] = $data;
            $data = [];
        }
        return $groupsArray;
    }

    public function renderQuestion($questions)
    {
        $questionsArray = [];
        foreach($questions as $question) {
            $data['id'] = $question['id'];
            $data['part_id'] = $question['part_id'];
            $data['group_id'] = $question['group_id'];
            $data['question'] = $question['question'];
            $data['audio'] = $question['audio'];
            $data['attachment'] = $question['attachment'];
            $data['order_in_test'] = $question['order_in_test'];
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
