<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Tymon\JWTAuth\Facades\JWTAuth;

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
            if ($e->getPrevious() instanceof \Illuminate\Session\TokenMismatchException) {
                return redirect('/');
            };
        });
    }


    /**
     * @param $request
     * @param Throwable $exception
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     * @throws Throwable
     */
    public function render($request, \Throwable $exception)
    {
        // Handle unauthenticated exception (JWT or otherwise)
        if ($exception instanceof AuthenticationException) {
            try {
                $token = JWTAuth::getToken();
                JWTAuth::checkOrFail($token);
            } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                // API request → return JSON
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'Token expired!',
                        'validation' => false
                    ], 419);
                }

                // Web request → redirect to login
                return redirect()->guest(route('login'))
                    ->withErrors(['token' => 'Your session has expired. Please log in again.']);
            } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
                // API request → return JSON
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'Unauthenticated, you must be logged in to access this resource.',
                        'validation' => false
                    ], 403);
                }

                // Web request → redirect to login
                return redirect()->guest(route('login'))
                    ->withErrors(['auth' => 'You must be logged in to access this page.']);
            }
        }

        return parent::render($request, $exception);
    }

}
