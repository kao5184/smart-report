<?php

namespace App\Exceptions;

use App\Http\Response;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return \App\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof ValidationException) {
            $errors = [];
            foreach ($e->validator->getMessageBag()->all() as $message) {
                $errors[] = [
                    'code' => BusinessException::PARAMETER_ERROR,
                    'message' => $message,
                ];
            }
            return Response::errors($errors, 422);
        }
        if ($e instanceof HttpException) {
            return Response::error(
                $e->getStatusCode(),
                $e->getMessage(),
                $e->getStatusCode()
            );
        }
        if (env('APP_ENV', 'prod') === 'prod') {
            return Response::error('SERVER_ERROR', '服务器内部错误', $this->httpCode($e->getCode()));
        }
        return Response::error('SERVER_ERROR', $e->getMessage(), $this->httpCode($e->getCode()));
//         return parent::render($request, $e);
    }

    protected function httpCode($code)
    {
        if (in_array($code, [
            500,
            403,
            422,
            401,
            404,
            405,
            422,
        ])) {
            return $code;
        } else {
            return 500;
        }
    }
}
