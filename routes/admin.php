<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;

Route::prefix('admin')->name('admin.')->group(function(){
    Route::middleware(['guest:admin', 'PreventBackHistory'])->group(function(){
        Route::view('/login', 'dashboard.admin.login')->name('login');
        Route::post('/check', [AdminController::class, 'check'])->name('check');
        Route::get('/verify', [AdminController::class, 'verify'])->name('verify');

        Route::get('/password/forgot', [AdminController::class,'showForgotForm'])->name('forgot.password.form');
        Route::post('/password/forgot', [AdminController::class,'sendResetLink'])->name('forgot.password.link');
        Route::get('/password/reset/{token}', [AdminController::class,'showResetForm'])->name('reset.password.form');
        Route::post('/password/reset', [AdminController::class,'resetPassword'])->name('reset.password');

    });

    Route::middleware(['auth:admin', 'is_admin_verify_email', 'PreventBackHistory'])->group(function(){
        Route::view('/home', 'dashboard.admin.home')->name('home');
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    });

});
