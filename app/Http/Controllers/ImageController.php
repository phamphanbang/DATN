<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ImageController extends Controller
{
    public function showImage($prefix, $filename)
    {
        $path = storage_path('app/' . $prefix . '/' . $filename);

        if (!file_exists($path)) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        $file = file_get_contents($path);

        return response($file, 200)->header('Content-Type', 'image/jpeg');
    }
}