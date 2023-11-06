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
        $blogPerpage = array_key_exists('maxResultCount', $request) ? $request['maxResultCount'] : config('constant.USER_PER_PAGE');
        $offset = array_key_exists('skipCount', $request) ? $request['skipCount'] : 0;
        return $this->blogRepository->index($request, $offset, $blogPerpage);
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
