<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro('success', function ($data, $status_code, $message = null) {
            return response()->json([
                'status' => 'success',
                'data' => $data,
                'message' => $message
            ], $status_code);
        });

        Response::macro('list', function ($data, $status_code) {
            return response()->json([
                'items' => $data['items'],
                'totalCount' => $data['totalCount']
            ], $status_code);
        });

        Response::macro('error', function ($error, $status_code) {
            return response()->json([
                'status' => 'fail',
                'message' => $error
            ], $status_code);
        });

        Response::macro('validateError', function ($error, $status_code) {
            $errorArray = [];
            foreach ($error->getMessages() as $key => $error) {
                $errorArray[] = [
                    "type" => $key,
                    "message" => $error[0]
                ];
            }
            return response()->json([
                'status' => 'fail',
                'message' => $errorArray
            ], $status_code);
        });
    }
}
