<?php
declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        $this->renderable(function (NotFoundHttpException $e, $request) {
            return response()->json([
                'error' => 'invalid_url',
                'error_description' => 'The requested URL was not found on this server.',
            ], Response::HTTP_NOT_FOUND);
        });

        $this->renderable(function (ModelNotFoundException $e, $request) {
            return response()->json([
                'error' => 'model_not_found',
                'error_description' => 'The requested model could not be found.',
            ], Response::HTTP_NOT_FOUND);
        });
    }
}
