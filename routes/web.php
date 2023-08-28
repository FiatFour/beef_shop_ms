<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\Middleware\SuperAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::view('login', 'dashboard.auth.login')->name('login');
Route::post('/check', [LoginController::class, 'check'])->name('check');


Route::get('/password/forgot', [ResetPasswordController::class,'showForgotForm'])->name('forgot.password.form');
Route::post('/password/forgot', [ResetPasswordController::class,'sendResetLink'])->name('forgot.password.link');
Route::get('/password/reset/{token}', [ResetPasswordController::class,'showResetForm'])->name('reset.password.form');
Route::post('/password/reset', [ResetPasswordController::class,'resetPassword'])->name('reset.password');


// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');






