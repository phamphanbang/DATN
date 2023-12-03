<?php

namespace App\Services;

use App\Repositories\ExamRepository;
use App\Repositories\TemplateRepository;

class HomeService
{
    public function __construct(
        protected ExamRepository $examRepository,
        protected TemplateRepository $templateRepository
    ) {
    }

    public function index()
    {
        $data['exams'] = $this->examRepository->getExamForHomePage();

        return $data;
    }

}
