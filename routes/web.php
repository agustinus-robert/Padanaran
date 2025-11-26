<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ChooseEducationController;

Route::view('/email_show', 'notify/mail');

Route::get('/', function () {
    //return redirect('/home');
    return redirect('login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/choose-education', [ChooseEducationController::class, 'index'])->name('choose.education');
    Route::post('/choose-education', [ChooseEducationController::class, 'store'])->name('choose.education.store');
});

    Route::get('/progress', function (Request $request) {
        $key = $request->query('schedule_key');
        if (!$key) {
            return response()->json(['percent' => 0]);
        }

        $value = cache()->get("progress_{$key}", 0);
        return response()->json([
            'percent' => intval($value)
        ]);
    });



Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware(['guest']); // ← Tanpa throttle:login

Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])
    ->middleware(['guest'])
    ->name('password.email');