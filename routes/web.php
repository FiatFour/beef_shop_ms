<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\Controllers\User\UserController;
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

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('user')->name('user.')->group(function(){
    Route::middleware(['guest:web', 'PreventBackHistory'])->group(function(){
        Route::view('/login', 'dashboard.user.login')->name('login');
        Route::view('/register', 'dashboard.user.register')->name('register');
        Route::post('/create', [UserController::class, 'create'])->name('create');
        Route::post('/check', [UserController::class, 'check'])->name('check');
    });

    Route::middleware(['auth:web', 'PreventBackHistory'])->group(function(){
        Route::view('/home', 'dashboard.user.home')->name('home');
        Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    });
});

Route::prefix('admin')->name('admin.')->group(function(){
    Route::middleware(['guest:admin', 'PreventBackHistory'])->group(function(){
        Route::view('/login', 'dashboard.admin.login')->name('login');
        // Route::view('/register', 'dashboard.admin.register')->name('register');
        // Route::post('/create', [AdminController::class, 'create'])->name('create');
        Route::post('/check', [AdminController::class, 'check'])->name('check');
    });

    Route::middleware(['auth:admin', 'PreventBackHistory'])->group(function(){
        Route::view('/home', 'dashboard.admin.home')->name('home');
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

    });

    // Route::middleware(['auth:admin', 'PreventBackHistory'])->group(function(){
    //     Route::view('/super/home', 'dashboard.super-admin.home')->name('superhome');
    // });
});

Route::prefix('super-admin')->name('super-admin.')->group(function(){
    Route::middleware(['guest:super-admin', 'PreventBackHistory'])->group(function(){
        Route::view('/login', 'dashboard.super-admin.login')->name('login');
        Route::view('/register', 'dashboard.super-admin.register')->name('register');
        Route::post('/create', [SuperAdminController::class, 'create'])->name('create');
        Route::post('/check', [SuperAdminController::class, 'check'])->name('check');
    });

    Route::middleware(['auth:super-admin','PreventBackHistory','super_admin'])->group(function(){
        Route::view('/home', 'dashboard.super-admin.home')->name('home');
        Route::post('/logout', [SuperAdminController::class, 'logout'])->name('logout');

    });
    // Route::middleware(['super_admin', 'PreventBackHistory'])->group(function(){
    //     Route::view('/home', 'dashboard.super-admin.home')->name('home');
    //     // Route::view('/super/home', 'dashboard.super-admin.home')->name('superhome');
    // });

});

// Route::get('super-admin/home', [SuperAdminController::class, 'superAdminHome'])->name('super-admin.home')->middleware('super_admin');





