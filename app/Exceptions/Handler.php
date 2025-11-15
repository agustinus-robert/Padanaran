<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $e)
    {
        if(!$request->expectsJson()) {
            return match (true) {
                $e instanceof AuthorizationException => $this->redirect($request, 'Maaf, Anda tidak memiliki hak untuk mengakses tautan tersebut!'),
                $e instanceof TokenMismatchException => $this->redirect($request, 'Sesi telah kadaluarsa, muat ulang halaman ini dan silakan lakukan proses kembali!'),
                default => parent::render($request, $e)
            };
        }

        return parent::render($request, $e);
    }

    /**
     * Get index page route via request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function redirect($request, $message)
    {
        return url()->previous() != $request->url() ? redirect()->back()->with('danger', $message) : abort(403, 'Unauthorized action.');
    }

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
}
