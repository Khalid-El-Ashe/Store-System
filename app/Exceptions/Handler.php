<?php

namespace App\Exceptions;

use \Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
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

        /**
         * in this block you can to handling the error message and Log it into the laravel.log file from (Storage Folder)
         * that is advance if you have team work in this project to can you all see the exceptions
         * (this Block is For Developers)
         */
        $this->reportable(function (QueryException $e) {
            if ($e->getCode() == 23000) {
                // Log::warning($e->getMessage());
                Log::channel('sql')->warning($e->getMessage()); //todo this channel-log is created by me in the (logging.php) file

                return false;
            }
            return true;
        });

        /**
         * i need to make this function to handle the error
         * in this block you can to handling any message error for any exception you want
         * like the error foreign key constraint 23000 or any error you want
         * (this Block to Showing the warning to users)
         */
        $this->renderable(function (QueryException $e, Request $request) {
            // i need expect the error 23000  which is foreign key constraint
            if ($e->getCode() == 23000) {
                $message = 'Foreign key constraint error: You cannot delete this category because it has related products.';
            } else {
                $message = 'Database error: ' . $e->getMessage();
            }

            //todo if you need to return or send the json API response
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $message,
                ], Response::HTTP_BAD_REQUEST); // you can change the status code
            }

            return redirect()->back()->withInput()->withErrors([
                'message' => $e->getMessage(),
            ])->with('info', $message);
        });

        // $this->renderable(function (Throwable $e, $request) {
        //     if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
        //         return response()->view('errors.404', [], 404);
        //     }
        //     if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
        //         return response()->view('errors.404', [], 404);
        //     }
        //     if ($e instanceof \Illuminate\Auth\AuthenticationException) {
        //         return response()->view('errors.401', [], 401);
        //     }
        //     if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
        //         return response()->view('errors.403', [], 403);
        //     }
        //     if ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
        //         return response()->view('errors.405', [], 405);
        //     }
        //     if ($e instanceof \Illuminate\Validation\ValidationException) {
        //         return response()->view('errors.422', [], 422);
        //     }
        //     // you can add more exception here
        // });
    }
}
