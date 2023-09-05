<?php

namespace App\Services;

use App\Repositories\ExamRepository;

class ExamService
{
    private $examRepository;
    public function __construct(ExamRepository $examRepository)
    {
        $this->examRepository = $examRepository;
    }

    public function store($request)
    {
        return $this->examRepository->store($request);
    }

}
