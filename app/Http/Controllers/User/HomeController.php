<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\HomeService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HomeController extends Controller
{
    public function __construct(
        protected HomeService $homeService
    ) {
    }

    public function index(Request $request)
    {
        $data = $this->homeService->index($request->all());
        return response()->show($data);
    }
}
