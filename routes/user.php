<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;

Route::prefix('user')->name('user.')->group(function(){
    Route::middleware(['guest:web', 'PreventBackHistory'])->group(function(){
        Route::view('/login', 'dashboard.user.login')->name('login');
        Route::view('/register', 'dashboard.user.register')->name('register');
        Route::post('/create', [UserController::class, 'create'])->name('create');
        Route::post('/check', [UserController::class, 'check'])->name('check');
        Route::get('/verify', [UserController::class, 'verify'])->name('verify');

        Route::get('/verification/{id}',[UserController::class,'verification']);
        Route::post('/verified',[UserController::class,'verifiedOtp'])->name('verifiedOtp');
        Route::get('/resend-otp',[UserController::class,'resendOtp'])->name('resendOtp');
    });

    Route::middleware(['auth:web', 'is_user_verify_email', 'PreventBackHistory'])->group(function(){
        Route::view('/home', 'dashboard.user.home')->name('home');
        Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    });
});
Route::get('/verification/{id}',[UserController::class,'verification']);

