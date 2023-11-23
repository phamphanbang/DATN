<?php

namespace App\Repositories;

use App\Exceptions\UserNotAuthorizeException;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Throwable;

class UserRepository
{
    private $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index($request, $offset, $limit, $sorting)
    {
        $query = $this->user;
        if (array_key_exists('role', $request) && $request['role']) {
            $query = $query->where('role', '=', $request['role']);
        }
        if (array_key_exists('search', $request) && $request['search']) {
            $query = $query->searchAttributes($query, $request['search']);
        }
        $query = $query->orderBy($sorting[0], $sorting[1]);
        $data['totalCount'] = $query->count();
        $data['items'] = $query->skip($offset)->take($limit)->get();

        return $data;
    }

    public function show($id)
    {
        try {
            $user = $this->user->findOrFail($id);
        } catch (Throwable $e) {
            throw new ModelNotFoundException(__('exceptions.userNotFound'));
        }
        return $user;
    }

    public function store($data)
    {
        $user = $this->user->create($data);
        return $user;
    }

    public function update($id, $data)
    {
        try {
            $user = $this->user->findOrFail($id);
        } catch (Throwable $e) {
            throw new ModelNotFoundException(__('exceptions.userNotFound'));
        }
        $user->update($data);
        return $this->user->findOrFail($id);
    }

    public function destroy($id)
    {
        try {
            $user = $this->user->findOrFail($id);
        } catch (Throwable $e) {
            throw new ModelNotFoundException(__('exceptions.userNotFound'));
        }
        $data['avatar'] = $user->avatar;
        $data['panel'] = $user->panel;
        $user->delete();
        return $data;
    }

    public function countAllUser()
    {
        return $this->user->all()->count();
    }
}
