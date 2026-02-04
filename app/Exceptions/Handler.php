<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Tymon\JWTAuth\Facades\JWTAuth;

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
     * @return void
     */
    public function register()
    {
        $this->renderable(function (Exception $e) {
            if ($e->getPrevious() instanceof \Illuminate\Session\TokenMismatchException) {
                return redirect('/');
            };
        });

    }


    /**
     * @param $request
     * @param Throwable $exception
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof AuthenticationException) {

            // 🔹 API requests (JWT)
            if ($request->expectsJson()) {
                try {
                    $token = JWTAuth::getToken();
                    JWTAuth::checkOrFail($token);
                } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                    return response()->json([
                        'error' => 'Token expired!',
                        'validation' => false
                    ], 419);
                } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
                    return response()->json([
                        'error' => 'Unauthenticated, you must be logged in to access this resource.',
                        'validation' => false
                    ], 403);
                }

                return response()->json([
                    'error' => 'Unauthenticated.',
                    'validation' => false
                ], 401);
            }

            // 🔹 Web requests
            return redirect()->guest(route('login'));
        }

        return parent::render($request, $exception);
    }

}
