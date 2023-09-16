<?php

namespace App\Services;

use App\Repositories\BlogRepository;

class BlogService
{

    public function __construct(
        protected BlogRepository $blogRepository
    ) {
    }

    public function index($request)
    {
        $page = $request->page ? $request->page : config('constant.DEFAULT_PAGE');
        $totalUser = $this->blogRepository->countAllBlog();
        $userPerPage = config('constant.USER_PER_PAGE');
        $totalPage = ceil($totalUser / $userPerPage);
        $checkPage = $page > $totalPage ? $totalPage : $page;
        $offset = ($checkPage - 1) * $userPerPage;
        return $this->blogRepository->index($request, $offset, $userPerPage);
    }

    public function show($id)
    {
        return $this->blogRepository->show($id);
    }

    public function store($request)
    {
        return $this->blogRepository->store($request);
    }

    public function update($id, $request)
    {
        return $this->blogRepository->update($id, $request);
    }

    public function destroy($id)
    {
        return $this->blogRepository->destroy($id);
    }
}
