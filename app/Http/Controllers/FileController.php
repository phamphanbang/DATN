<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class FileController extends Controller
{
    public function showImage($type, $prefix, $filename)
    {
        $path = storage_path('app/' . $type . '/' . $prefix . '/' . $filename);

        if (!file_exists($path)) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        $file = file_get_contents($path);

        return response($file, 200)->header('Content-Type', 'image/jpeg');
    }

    public function showAudio($type, $prefix, $filename)
    {
        $path = storage_path('app/' . $type . '/' . $prefix . '/' . $filename);

        if (!file_exists($path)) {
            return response("", 200);
        }

        $file = file_get_contents($path);

        return response($file, 200)->header('Content-Type', 'audio/mpeg');
    }
}
