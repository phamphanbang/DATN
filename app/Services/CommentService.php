<?php

namespace App\Services;

use App\Repositories\CommentRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CommentService
{

    public function __construct(protected CommentRepository $commentRepository)
    {
    }

    public function index($request,$id,$type)
    {
        // $data['totalCount'] = $this->userRepository->countAllUser();
        $itemPerPage = array_key_exists('maxResultCount', $request) ? $request['maxResultCount'] : config('constant.USER_PER_PAGE');
        $offset = array_key_exists('skipCount', $request) ? $request['skipCount'] : 0;
        $sorting = array_key_exists('sorting', $request) ? explode(" ", $request['sorting']) : ['id', 'asc'];
        $request['commentable_id'] = $id;
        $request['commentable_type'] = $type;
        $data = $this->commentRepository->index($request, $offset, $itemPerPage);

        return $data;
    }

    public function show($id)
    {
        $comment = $this->commentRepository->show($id);

        return $comment;
    }

    public function store($request)
    {
        $data['user_id'] = $request['user_id'];
        $data['commentable_id'] = $request['commentable_id'];
        $data['commentable_type'] = $request['commentable_type'];
        $data['comment'] = $request['comment'];
        $comment = $this->commentRepository->store($data);

        return $comment;
    }

    public function update($id, $request)
    {
        $data['user_id'] = $request['user_id'];
        $data['commentable_id'] = $request['commentable_id'];
        $data['commentable_type'] = $request['commentable_type'];
        $data['comment'] = $request['comment'];
        $comment = $this->commentRepository->update($id,$data);
        return $comment;
    }

    public function destroy($id)
    {
        $comment = $this->commentRepository->destroy($id);
        return true;
    }
}
