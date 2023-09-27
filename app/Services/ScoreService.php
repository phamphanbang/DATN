<?php

namespace App\Services;

use App\Repositories\ScoreRepository;

class ScoreService
{

    public function __construct(
        protected ScoreRepository $scoreRepository
    ) {
    }

    public function index()
    {
        return $this->scoreRepository->index();
    }

    public function show($id)
    {
        return $this->scoreRepository->show($id);
    }

    public function store($request)
    {
        return $this->scoreRepository->store($request);
    }

    public function update($id, $request)
    {
        return $this->scoreRepository->update($id, $request);
    }

    public function destroy($id)
    {
        return $this->scoreRepository->destroy($id);
    }
}
