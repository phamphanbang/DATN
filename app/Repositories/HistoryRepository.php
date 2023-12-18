<?php

namespace App\Repositories;

use App\Models\History;
use App\Models\HistoryAnswer;
use App\Models\HistoryPart;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Throwable;

class HistoryRepository
{
    public function __construct(
        protected History $history,
        protected HistoryPart $historyPart,
        protected HistoryAnswer $historyAnswer
    ) {
    }

    public function storeHistory($request)
    {
        $request['right_questions'] = 0;
        $request['score'] = 0;
        $request['total_questions'] = 0;
        $history = $this->history->create($request);
        return $history;
    }

    public function storeHistoryPart($request)
    {
        $part = $this->historyPart->create($request);
        return $part;
    }

    public function storeHistoryAnswer($request)
    {
        $answer = $this->historyAnswer->create($request);
        return $answer;
    }

    public function show($id)
    {
        try {
            $history = $this->history->findOrFail($id);
        } catch (Throwable $e) {
            throw new ModelNotFoundException('Can not find exam with id ' . $id);
        }
        return $history;
    }

    public function getExamHistory($exam_id, $user_id)
    {
        try {
            $history = $this->history
                ->with(['parts'])
                ->where('exam_id', $exam_id)
                ->where('user_id', $user_id)
                ->orderBy('created_at', 'DESC')->get();
        } catch (Throwable $e) {
            throw new ModelNotFoundException('Can not find exam with id ');
        }
        return $history;
    }

    public function index($request, $offset, $limit, $sorting, $userId)
    {
        $query = $this->history;
        if (array_key_exists('search', $request) && $request['search']) {
            $query = $query->searchAttributes($query, $request['search']);
        }
        if (array_key_exists('time', $request) && $request['time']) {

            $query = $query->whereDateBetween(
                'created_at',
                (new Carbon)->subDays((int)$request['time'])->startOfDay()->toDateString(),
                (new Carbon)->now()->endOfDay()->toDateString()
            );
        }
        $stat['number_of_test'] = $query->distinct()->count('exam_id');
        $query = $query->with(['parts', 'test:id,name'])->where('user_id', $userId);
        $data['totalCount'] = $query->count();
        $data['items'] = $query->orderBy('created_at', 'DESC')->skip($offset)->take($limit)->get();

        // $this->historyStatistic($request,$userId);
        $stat['listening_total_questions'] = 0;
        $stat['listening_right_questions'] = 0;
        $stat['reading_total_questions'] = 0;
        $stat['reading_right_questions'] = 0;
        $total_duration = 0;
        $part_type = collect([]);
        foreach ($data['items'] as $history) {
            $explode = explode(':', $history['duration']);
            $duration = (int)$explode[2] + (int)$explode[1] * 60 + (int)$explode[0] * 60 * 60;
            $total_duration += $duration;
            foreach ($history['parts'] as $part) {
                // $type = $part->test_part->template['part_type'];
                $type = "";
                if ($part_type->has($part['part_id'])) {
                    $type = $part_type->get($part['part_id']);
                } else {
                    $type = $part->test_part->template['part_type'];
                    $part_type[$part['part_id']] = $type;
                }
                $answers = $part->answers;
                $total_question = $answers->count();
                $total_right = $answers->filter(function ($value, $key) {
                    return $value['is_right'] == 1;
                })->count();
                if ($type == 'reading') {
                    $stat['reading_total_questions'] += $total_question;
                    $stat['reading_right_questions'] += $total_right;
                } else {
                    $stat['listening_total_questions'] += $total_question;
                    $stat['listening_right_questions'] += $total_right;
                }
            }
        }
        $stat['total_duration'] = $this->secondsToTime($total_duration);
        $fulltest = $data['items']->filter(function ($value, $key) {
            return $value['test_type'] == 'fulltest';
        });

        $stat['min_score'] = $fulltest->min('score');
        $stat['max_score'] = $fulltest->max('score');
        $data['stat'] = $stat;

        return $data;
    }

    function secondsToTime($inputSeconds)
    {
        $secondsInAMinute = 60;
        $secondsInAnHour  = 60 * $secondsInAMinute;

        $hours = floor($inputSeconds / $secondsInAnHour);

        $minuteSeconds = $inputSeconds % $secondsInAnHour;
        $minutes = floor($minuteSeconds / $secondsInAMinute);

        $remainingSeconds = $minuteSeconds % $secondsInAMinute;
        $seconds = ceil($remainingSeconds);

        return sprintf("%02d", $hours) . ":"
            . sprintf("%02d", $minutes) . ":"
            . sprintf("%02d", $seconds);
    }
}
