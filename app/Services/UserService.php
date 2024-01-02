<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index($request)
    {
        // $data['totalCount'] = $this->userRepository->countAllUser();
        $itemPerPage = array_key_exists('maxResultCount', $request) ? $request['maxResultCount'] : config('constant.USER_PER_PAGE');
        $offset = array_key_exists('skipCount', $request) ? $request['skipCount'] : 0;
        $sorting = array_key_exists('sorting', $request) ? explode(" ", $request['sorting']) : ['id', 'asc'];

        $data = $this->userRepository->index($request, $offset, $itemPerPage, $sorting);

        return $data;
    }

    public function show($id)
    {
        $user = $this->userRepository->show($id);
        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'avatar' => $user->avatar,
            'panel' => $user->panel
        ];

        return $data;
    }

    public function store($request)
    {
        $data = [];
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['role'] = $request->role;
        $data['password'] = Hash::make($request->password);
        $data['avatar'] = 'defaultAvatar.png';
        $data['panel'] = 'defaultPanel.png';
        $user = $this->userRepository->store($data);
        $keys = ['avatar', 'panel'];
        foreach ($keys as $key) {
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $extension = $file->getClientOriginalExtension();
                $fileName = $user->id . '_' . $key . '.' . $extension;
                $file->storeAs('users', $fileName);

                $data[$key] = $fileName;
            }
        }

        return $this->userRepository->update($user->id, $data);
    }

    public function update($id, $request)
    {
        $data = [];
        $user = $this->userRepository->show($id);
        $data['name'] = $request['name'];
        $data['email'] = $request['email'];
        $data['avatar'] = $request['avatar'] ? $request['avatar'] : 'defaultAvatar.png';
        // $data['password'] = Hash::make($request['password']);
        // $data['panel'] = $request['avatar'];
        $keys = ['avatar'];
        foreach ($keys as $key) {
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $extension = $file->getClientOriginalExtension();
                $fileName = $id . '_' . $key . '.' . $extension;
                $file->storeAs('users', $fileName);
                $data[$key] = $fileName;
            } elseif ($user[$key] !== $data[$key]) {
                if (Storage::exists('users/' . $user[$key])) {
                    Storage::delete('users/' . $user[$key]);
                    $data[$key] = 'defaultAvatar.png';
                }
            }
        }

        return $this->userRepository->update($id, $data);
    }

    public function resetPassword($id, $request)
    {
        $data = [];
        $data['password'] = Hash::make($request['password']);

        return $this->userRepository->update($id, $data);
    }

    public function destroy($id)
    {
        $fileLinks = $this->userRepository->destroy($id);
        foreach ($fileLinks as $file) {
            if (Storage::exists('users/' . $file) && $file != 'default.png') {
                Storage::delete('users/' . $file);
            }
        }
        return true;
    }
}
