<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Models\Score;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Throwable;

class CommentRepository
{
    public function __construct(protected Comment $comment)
    {
    }

    public function index($request, $offset, $limit)
    {
        $query = $this->comment;
        if (array_key_exists('commentable_id', $request) && $request['commentable_id']) {
            $query = $query->where('commentable_id', $request['commentable_id']);
        }
        if (array_key_exists('commentable_type', $request) && $request['commentable_type']) {
            $query = $query->where('commentable_type', $request['commentable_type']);
        }
        $query = $query->with(['user' => function ($query) {
            $query->select('id', 'name','avatar');
        }]);
        $data['totalCount'] = $query->count();
        $data['items'] = $query->orderBy('created_at', 'DESC')->skip($offset)->take($limit)->get();

        return $data;
    }

    public function show($id)
    {
        try {
            $score = $this->comment->findOrFail($id);
        } catch (Throwable $e) {
            throw new ModelNotFoundException('Không tìm thấy bình luận này');
        }
        return $score;
    }

    public function store($data)
    {
        $comment = $this->comment->create($data);
        return $comment;
    }

    public function update($id, $data)
    {
        try {
            $comment = $this->comment->findOrFail($id);
        } catch (Throwable $e) {
            throw new ModelNotFoundException('Không tìm thấy bình luận này');
        }
        $comment->update($data);
        return $this->comment->findOrFail($id);
    }

    public function destroy($id)
    {
        try {
            $comment = $this->comment->findOrFail($id);
        } catch (Throwable $e) {
            throw new ModelNotFoundException(__('exceptions.userNotFound'));
        }
        $comment->delete();
        return true;
    }
}
