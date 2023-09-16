<?php

namespace App\Repositories;

use App\Models\Score;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
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
        $data = $this->score->get();

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
        $score_id = $this->score->create($data)->id;
        return $this->score->findOrFail($score_id);
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

    public function destroy($id)
    {
        try {
            $score = $this->score->findOrFail($id);
        } catch (Throwable $e) {
            throw new ModelNotFoundException(__('exceptions.scoreNotFound'));
        }
        $score->delete();
        return true;
    }
}
