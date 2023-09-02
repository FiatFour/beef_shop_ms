<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\SupplierController;


// Route::prefix('admin')->name('admin.')->group(function(){
//     Route::middleware(['guest:employee', 'PreventBackHistory'])->group(function(){
//         // Route::view('/login', 'dashboard.admin.login')->name('login');
//         // Route::post('/check', [AdminController::class, 'check'])->name('check');
//         Route::get('/verify', [AdminController::class, 'verify'])->name('verify');


//     });

//     Route::middleware(['auth:employee', 'is_employee_verify_email', 'PreventBackHistory', 'is_admin'])->group(function(){
//         Route::view('/home', 'dashboard.admin.home')->name('home');
//         Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

//         Route::post('/create', [AdminController::class, 'create'])->name('create');
//         Route::post('/check', [AdminController::class, 'check'])->name('check');

//         // Employee
//         Route::get('/employees',[AdminController::class, 'index'])->name('employee');
//         Route::get('/employees/create', [AdminController::class, 'create'])->name('createEmployee');
//         Route::post('/employees/add',[AdminController::class, 'store'])->name('addEmployee');
//         Route::get('/employees/verify', [AdminController::class,'verify'])->name('verify');
//         Route::get('/employees/edit/{id}',[AdminController::class, 'edit'])->name('editEmployee');
//         Route::put('/employees/update/{id}',[AdminController::class, 'update'])->name('updateEmployee');

//         //Supplier
//         Route::get('/suppliers',[SupplierController::class, 'index'])->name('supplier');
//         Route::post('/suppliers/add',[SupplierController::class, 'store'])->name('addSupplier');
//         Route::get('/suppliers/edit/{id}',[SupplierController::class, 'edit'])->name('editSupplier');
//         Route::post('/suppliers/update/{id}',[SupplierController::class, 'update']);
//         Route::get('/suppliers/delete/{id}',[SupplierController::class, 'delete']);

//         Route::controller(App\Http\Controllers\CowController::class)->group(function(){
//             Route::get('/cows', 'index')->name('cow');
//             // Route::post('/cow/add', 'create');
//             Route::get('/cows/create', 'create')->name('createCow');
//             Route::post('/cows', 'store')->name('addCow');

//             Route::get('/cows/edit/{cow_id}', 'edit');
//             Route::put('/cows/update/{cow_id}', 'update');
//             Route::get('/cows/delete/{id}' , 'destroy');
//         });
//     });

// });
