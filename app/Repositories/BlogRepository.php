<?php

namespace App\Repositories;

use App\Models\Blog;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Throwable;

class BlogRepository
{
    private $blog;
    public function __construct(Blog $blog)
    {
        $this->blog = $blog;
    }

    public function index($request, $offset, $limit, $sorting)
    {
        $query = $this->blog;
        if (array_key_exists('search',$request) && $request['search']) {
            $query = $query->searchAttributes($query, $request['search']);
        }
        $query = $query->withCount('comments');
        $query = $query->orderBy($sorting[0], $sorting[1]);
        $data['totalCount'] = $query->count();
        $data['items'] = $query->skip($offset)->take($limit)->get();

        return $data;
    }

    public function getBlogForHomePage()
    {
        $query = $this->blog;
        $limit = 8;
        $query = $query->withCount(['comments']);
        $data = $query->orderBy('created_at', 'DESC')->take($limit)->get();

        return $data;
    }

    public function show($id)
    {
        try {
            $blog = $this->blog->findOrFail($id);
        } catch (Throwable $e) {
            throw new ModelNotFoundException(__('exceptions.blogNotFound'));
        }
        return $blog;
    }

    public function store($data)
    {
        $blog = $this->blog->create($data);
        $panel = 'blog-' . $blog->id . '-panel';
        $thumbnail = 'blog-' . $blog->id . '-thumbnail';
        // $blog->panel = $this->fileHandler($blog, $panel, $data, 'panel');
        $blog->thumbnail = $this->fileHandler($blog, $thumbnail, $data, 'thumbnail');
        $blog->save();
        return $blog;
    }

    public function update($id, $data)
    {
        try {
            $blog = $this->blog->findOrFail($id);
        } catch (Throwable $e) {
            throw new ModelNotFoundException(__('exceptions.blogNotFound'));
        }
        // $panel = 'blog-' . $blog->id . '-panel';
        $thumbnail = 'blog-' . $blog->id . '-thumbnail';
        // $blog->panel = $this->fileHandler($blog, $panel, $data, 'panel');
        $blog->thumbnail = $this->fileHandler($blog, $thumbnail, $data, 'thumbnail');
        $blog->name = $data['name'];
        $blog->post = $data['post'];
        $blog->save();
        return $blog;
    }

    public function destroy($id)
    {
        try {
            $blog = $this->blog->findOrFail($id);
        } catch (Throwable $e) {
            throw new ModelNotFoundException(__('exceptions.blogNotFound'));
        }
        $this->removeFile($blog->panel);
        $this->removeFile($blog->thumbnail);
        $blog->delete();
        return true;
    }

    public function fileHandler($model, $fileName, $data, $type)
    {
        $res = $model[$type] ? $model[$type] : 'default';
        if ($data[$type] == null && $model[$type] != null) {
            $res = $this->removeFile($model[$type]);
        }
        if (request()->file($type)) {
            $res = $this->saveFile($fileName, $type);
        }
        return $res;
    }

    public function saveFile($saveFile, $type)
    {
        $file = request()->file($type);
        $extension = $file->getClientOriginalExtension();
        $fileName = $saveFile . '.' .$extension;
        $file->storeAs('blogs/', $fileName);
        return $fileName;
    }

    public function removeFile($fileName)
    {
        $linkToFile = 'blogs/' . $fileName;
        if (Storage::exists($linkToFile)) {
            Storage::delete($linkToFile);
        }
        return null;
    }

    public function countAllBlog()
    {
        return $this->blog->all()->count();
    }
}
