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

    public function index($request, $offset, $limit)
    {
        $data = $this->user;
        if ($request->role) {
            $data = $data->where('role', '=', $request->role);
        }
        if ($request->search) {
            $data = $data->searchAttributes($data, $request->search);
        }
        $data = $data->skip($offset)->take($limit)->get();

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
        return $this->user->create($data);
    }

    public function update($id, $data)
    {
        try {
            $user = $this->user->findOrFail($id);
        } catch (Throwable $e) {
            throw new ModelNotFoundException(__('exceptions.userNotFound'));
        }
        $user->update($data);
        return true;
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
