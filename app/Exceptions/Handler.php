<?php

namespace App\Exceptions;

use Throwable;
use App\Helpers;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        // \Illuminate\Database\Eloquent\ModelNotFoundException::class,
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            $message = $exception->errors()['message'];
        }

        if (config('app.env') === 'production') {
            $message = $exception->getMessage();
            Helpers::abort($message);
        }

        parent::report($exception);
    }
}
