<?php

namespace App\Repositories;

use App\Models\Score;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class ScoreRepository
{
    private $score;
    public function __construct(Score $score)
    {
        $this->score = $score;
    }

    public function index()
    {
        $reading = DB::table("scores", "s1")
            ->leftjoin("scores as s2", function ($join) {
                $join->on("s2.questions", "=", "s1.questions");
                $join->on("s1.id", "<>", "s2.id");
            })
            ->where("s1.type", "=", "reading")
            ->orderBy('s1.questions')
            ->select(
                "s1.questions as questions",
                "s1.score as reading_score",
                "s1.id as reading_id",
                "s2.score as listening_score",
                "s2.id as listening_id"
            );
        $query = DB::table("scores", "s1")
            ->rightjoin("scores as s2", function ($join) {
                $join->on("s2.questions", "=", "s1.questions");
                $join->on("s1.id", "<>", "s2.id");
            })
            ->where("s2.type", "=", "listening")
            ->orderBy('s2.questions')
            ->select(
                "s2.questions as questions",
                "s1.score as reading_score",
                "s1.id as reading_id",
                "s2.score as listening_score",
                "s2.id as listening_id"
            )
            ->union($reading);

        $data['totalCount'] = $query->get()->count();
        $data['items'] = $query->get();


        return $data;
    }

    public function show($id)
    {
        try {
            $score = $this->score->findOrFail($id);
        } catch (Throwable $e) {
            throw new ModelNotFoundException(__('exceptions.scoreNotFound'));
        }
        return $score;
    }

    public function store($data)
    {
        $score = Score::where('type', '=', $data['type'])->where('questions', '=', $data['questions'])->first();
        if ($score) {
            $score->update($data);
        } else {
            $score = $this->score->create($data);
        }
        return $score;
    }

    public function update($id, $data)
    {
        try {
            $score = $this->score->findOrFail($id);
        } catch (Throwable $e) {
            throw new ModelNotFoundException(__('exceptions.scoreNotFound'));
        }
        $score->update($data);
        return $this->score->findOrFail($id);
    }

    public function destroy($data)
    {
        $score = Score::where('type', '=', $data['type'])->where('questions', '=', $data['questions'])->first();
        if (!$score) {
            throw new ModelNotFoundException(__('exceptions.scoreNotFound'));
        }
        $score->delete();
        return true;
    }
}
