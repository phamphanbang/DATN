<?php

namespace App\Services;

use App\Repositories\BlogRepository;
use App\Repositories\ExamRepository;
use App\Repositories\TemplateRepository;

class HomeService
{
    public function __construct(
        protected ExamRepository $examRepository,
        protected TemplateRepository $templateRepository,
        protected BlogRepository $blogRepository
    ) {
    }

    public function index()
    {
        $data['exams'] = $this->examRepository->getExamForHomePage();
        $data['blogs'] =$this->blogRepository->getBlogForHomePage();

        return $data;
    }

}
