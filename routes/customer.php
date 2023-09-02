<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\CustomerController;

// Route::prefix('customer')->name('customer.')->group(function(){
//     Route::middleware(['guest:customer', 'PreventBackHistory'])->group(function(){
//         Route::view('/login', 'dashboard.customer.login')->name('login');
//         Route::view('/register', 'dashboard.customer.register')->name('register');
//         Route::post('/create', [CustomerController::class, 'create'])->name('create');
//         Route::post('/check', [CustomerController::class, 'check'])->name('check');
//         Route::get('/verify', [CustomerController::class,'verify'])->name('verify');

//         Route::get('/password/forgot', [CustomerController::class,'showForgotForm'])->name('forgot.password.form');
//         Route::post('/password/forgot', [CustomerController::class,'sendResetLink'])->name('forgot.password.link');
//         Route::get('/password/reset/{token}', [CustomerController::class,'showResetForm'])->name('reset.password.form');
//         Route::post('/password/reset', [CustomerController::class,'resetPassword'])->name('reset.password');

//     });

//     Route::middleware(['auth:customer', 'is_customer_verify_email', 'PreventBackHistory'])->group(function(){
//         Route::view('/home', 'dashboard.customer.home')->name('home');
//         Route::post('/logout', [CustomerController::class, 'logout'])->name('logout');

//     });
// });

