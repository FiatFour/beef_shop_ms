<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Employee\EmployeeController;

Route::prefix('employee')->name('employee.')->group(function(){
    Route::middleware(['guest:employee', 'PreventBackHistory'])->group(function(){
        // Route::view('/login', 'dashboard.employee.login')->name('login');
        // Route::post('/check', [EmployeeController::class, 'check'])->name('check');
        // Route::get('/verify', [EmployeeController::class, 'verify'])->name('verify');

        // Route::get('/password/forgot', [EmployeeController::class,'showForgotForm'])->name('forgot.password.form');
        // Route::post('/password/forgot', [EmployeeController::class,'sendResetLink'])->name('forgot.password.link');
        // Route::get('/password/reset/{token}', [EmployeeController::class,'showResetForm'])->name('reset.password.form');
        // Route::post('/password/reset', [EmployeeController::class,'resetPassword'])->name('reset.password');

        Route::view('/register', 'dashboard.employee.register')->name('register');
        Route::post('/create', [EmployeeController::class, 'create'])->name('create');

    });

    Route::middleware(['auth:employee', 'is_employee_verify_email', 'PreventBackHistory'])->group(function(){
        Route::view('/home', 'dashboard.employee.home')->name('home');
    });

});
