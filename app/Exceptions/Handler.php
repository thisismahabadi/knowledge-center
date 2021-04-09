<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param Throwable $e
     *
     * @return object
     */
    public function render($request, Throwable $e): object
    {
        if ($e instanceof ValidationException) {
            return (new Controller)->setResponse(Controller::ERROR, $e->getMessage() ?: "An error occurred.", Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return (new Controller)->setResponse(Controller::ERROR, $e->getMessage() ?: "An error occurred.", method_exists($e, 'getStatusCode') ? $e->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
