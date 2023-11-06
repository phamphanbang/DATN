<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        dd($e);
        $message = $e->getMessage();
        $statusCode = $e->getCode();
        switch (true) {
            case $e instanceof ModelNotFoundException:
                $message = $e->getMessage();
                $statusCode = Response::HTTP_NOT_FOUND;
                break;
            case $e instanceof UserNotFoundException:
                $message = __('exceptions.authenFail');
                $statusCode = Response::HTTP_UNAUTHORIZED;
                break;
            case $e instanceof UserNeedLoginException:
                $message = __('exceptions.needLogin');
                $statusCode = Response::HTTP_UNAUTHORIZED;
                break;
            default:
                $message = __('exceptions.serverError');
                $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->error($message, $statusCode);
    }
}
