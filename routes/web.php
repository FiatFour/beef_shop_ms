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

Route::get('login', [LoginController::class, 'index'])->name('login')->middleware('PreventBackHistory');
Route::post('/check', [LoginController::class, 'check'])->name('check');


Route::controller(App\Http\Controllers\ResetPasswordController::class)->group(function(){
    Route::get('/password/forgot', 'showForgotForm')->name('forgot.password.form');
    Route::post('/password/forgot', 'sendResetLink')->name('forgot.password.link');
    Route::get('/password/reset/{token}', 'showResetForm')->name('reset.password.form');
    Route::post('/password/reset', 'resetPassword')->name('reset.password');
});


// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');






