<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\SuperAdminController;

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
