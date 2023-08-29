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

        Response::macro('error', function ($error, $status_code) {
            return response()->json([
                'status' => 'fail',
                'error' => $error
            ], $status_code);
        });
    }
}
