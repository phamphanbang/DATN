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
        $userPerPage = array_key_exists('maxResultCount', $request) ? $request['maxResultCount'] : config('constant.USER_PER_PAGE');
        $offset = array_key_exists('skipCount', $request) ? $request['skipCount'] : 0;

        $data = $this->userRepository->index($request, $offset, $userPerPage);

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
        $keys = ['avatar', 'panel'];
        foreach ($keys as $key) {
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $extension = $file->getClientOriginalExtension();
                $fileName = $data['name'] . '_' . $key . '.' . $extension;
                $file->storeAs('users', $fileName);

                $data[$key] = $fileName;
            }
        }

        return $this->userRepository->store($data);
    }

    public function update($id, $request)
    {
        $data = [];
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);
        $data['avatar'] = $request->avatar;
        $data['panel'] = $request->panel;
        $keys = ['avatar', 'panel'];
        foreach ($keys as $key) {
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $extension = $file->getClientOriginalExtension();
                $fileName = $data['name'] . '_' . $key . '.' . $extension;
                $file->storeAs('users', $fileName);

                $data[$key] = $fileName;
            }
        }

        return $this->userRepository->update($id, $request);
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
