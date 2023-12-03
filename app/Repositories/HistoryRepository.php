<?php

namespace App\Repositories;

use App\Models\History;
use App\Models\HistoryAnswer;
use App\Models\HistoryPart;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

    public function showWithRelationship($id)
    {
        try {
            $history = $this->history->with(['parts','parts.answers'])->findOrFail($id);
        } catch (Throwable $e) {
            throw new ModelNotFoundException('Can not find exam with id ' . $id);
        }
        return $history;
    }
}
