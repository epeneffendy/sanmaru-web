<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($request->wantsJson()) {
            return $this->apiHandler($request, $e);
        }

        return parent::render($request, $e);
    }

    private function apiHandler($request, Exception $e)
    {
        switch (true) {
            case $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException:
                $statusCode = 404;
                $message = 'Not Found';
                break;
            case $e instanceof \Illuminate\Auth\AuthenticationException:
                $statusCode = 401;
                $message = 'Unauthorized';
                break;
            case $e instanceof ValidationException:
                return $this->convertValidationExceptionToResponse($e, $request);
            default:
                $message = $e->getMessage();
                $statusCode = 422;
        }
        return response()->json(['message' => $message], $statusCode);
    }
}
