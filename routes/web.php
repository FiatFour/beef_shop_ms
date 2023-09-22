<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ProductSubCategoryController;
use App\Http\Controllers\Admin\TempImagesController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SupplierController;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', [FrontController::class, 'index'])->name('front.home');
Route::get('/shop/{categorySlug?}/{subCategorySlug?}', [ShopController::class, 'index'])->name('front.shop');
Route::get('/product/{slug}', [ShopController::class, 'product'])->name('front.product');

Route::controller(App\Http\Controllers\CartController::class)->group(function () {
    Route::get('/cart', 'cart')->name('front.cart');
    Route::post('/add-to-cart', 'addToCart')->name('front.addToCart');
    Route::post('/update-cart', 'updateCart')->name('front.updateCart');
    Route::post('/delete-cart', 'deleteItem')->name('front.deleteItem.cart');
    Route::get('/checkout', 'checkout')->name('front.checkout');
    Route::post('/process-checkout', 'processCheckout')->name('front.processCheckout');
    Route::get('/thanks/{orderId}', 'thankyou')->name('front.thankyou');
});

// Route::group(['prefix' => 'account'], function () {
    Route::controller(App\Http\Controllers\AuthController::class)->group(function () {
        Route::group(['middleware' => 'guest'], function () {
            Route::get('/login', 'login')->name('account.login');
            Route::post('/login', 'authenticate')->name('account.authenticate');

            Route::get('/register', 'register')->name('account.register');
            Route::post('/process-register', 'processRegister')->name('account.processRegister');
            Route::get('/verify', 'verifyCustomer')->name('account.verifyCustomer');
        });

        Route::middleware(['auth:customer', 'is_customer_verify_email'])->group(function () {
            Route::get('/profile', 'profile')->name('account.profile');
            Route::get('/logout', 'logout')->name('account.logout');
        });
    });
// });



Route::get('/xlogin', [LoginController::class, 'index'])->name('login')->middleware('PreventBackHistory');
Route::post('/check', [LoginController::class, 'checkLogin'])->name('checkLogin');
Route::get('/xlogout', [LoginController::class, 'logout'])->name('logoutAll');

Route::controller(App\Http\Controllers\Auth\ResetPasswordController::class)->group(function () {
    Route::get('/password/forgot', 'showForgotForm')->name('forgotPasswordForm');
    Route::post('/password/forgot', 'sendResetLink')->name('resetPasswordLink');
    Route::get('/password/reset/{token}', 'showResetForm')->name('resetPasswordForm');
    Route::post('/password/reset', 'resetPassword')->name('resetPassword');
});

Route::prefix('customer')->name('customer.')->group(function () {
    Route::middleware(['guest:customer', 'PreventBackHistory'])->group(function () {
        Route::view('/xregister', 'customer.register')->name('registerCustomer');
        Route::post('/xcreate', [CustomerController::class, 'createCustomer'])->name('createCustomer');
        Route::post('/xcheck', [CustomerController::class, 'checkCustomer'])->name('checkCustomer');
        Route::get('/xverify', [CustomerController::class, 'verifyCustomer'])->name('verifyCustomer');
    });

    Route::middleware(['auth:customer', 'is_customer_verify_email', 'PreventBackHistory'])->group(function () {
        Route::view('/home', 'customer.home')->name('home');
    });
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['guest:employee', 'PreventBackHistory'])->group(function () {
        // Route::view('/login', 'dashboard.admin.login')->name('login');
        // Route::post('/check', [AdminController::class, 'check'])->name('check');
        Route::get('/verify', [AdminController::class, 'verify'])->name('verify');
    });

    Route::middleware(['auth:employee', 'is_employee_verify_email', 'PreventBackHistory', 'is_admin'])->group(function () {
        Route::view('/dashboard', 'admin.dashboard')->name('home');

        Route::get('/getSlug', function (Request $request) {
            $slug = '';
            if (!empty($request->title)) {
                $slug = Str::slug($request->title);
            }
            return response()->json([
                'status' => true,
                'slug' => $slug
            ]);
        })->name('getSlug');

        // Category Route
        Route::controller(App\Http\Controllers\Admin\CategoryController::class)->group(function () {
            Route::get('/categories', 'index')->name('categories.index');
            Route::get('/categories/create', 'create')->name('categories.create');
            Route::post('/categories', 'store')->name('categories.store');
            Route::get('/categories/edit/{id}', 'edit')->name('categories.edit');
            Route::put('/categories/{id}', 'update')->name('categories.update');
            Route::delete('/categories/{id}', 'destroy')->name('categories.delete');
            // Route::get('/categories/verify','verify')->name('verifyCategory');

        });

        // temp-images.create
        Route::post('/upload-temp-image', [TempImagesController::class, 'create'])->name('temp-images.create');

        // Sub category
        Route::controller(App\Http\Controllers\Admin\SubCategoryController::class)->group(function () {
            Route::get('/sub-categories', 'index')->name('sub-categories.index');
            Route::get('/sub-categories/create', 'create')->name('sub-categories.create');
            Route::post('/sub-categories', 'store')->name('sub-categories.store');
            Route::get('/sub-categories/edit/{id}', 'edit')->name('sub-categories.edit');
            Route::put('/sub-categories/{id}', 'update')->name('sub-categories.update');
            Route::delete('/sub-categories/{id}', 'destroy')->name('sub-categories.delete');
        });

        // Cow gene
        Route::controller(App\Http\Controllers\Admin\CowGenesController::class)->group(function () {
            Route::get('/cow-genes', 'index')->name('cow-genes.index');
            Route::get('/cow-genes/create', 'create')->name('cow-genes.create');
            Route::post('/cow-genes', 'store')->name('cow-genes.store');

            Route::get('/cow-genes/edit/{id}', 'edit')->name('cow-genes.edit');
            Route::put('/cow-genes/{id}', 'update')->name('cow-genes.update');
            Route::delete('/cow-genes/{id}', 'destroy')->name('cow-genes.delete');
        });

        // Product
        Route::controller(App\Http\Controllers\Admin\ProductController::class)->group(function () {
            Route::get('/products', 'index')->name('products.index');
            Route::get('/product-subcategories', [ProductSubCategoryController::class, 'index'])->name('product-subcategories.index');
            Route::get('/products/create', 'create')->name('products.create');
            Route::post('/products', 'store')->name('products.store');

            Route::get('/products/edit/{id}', 'edit')->name('products.edit');
            Route::put('/products/{id}', 'update')->name('products.update');
            Route::post('/products-images/update', [ProductImageController::class, 'update'])->name('product-images.update');
            Route::delete('/products/{id}', 'destroy')->name('products.delete');
            Route::delete('/products-images', [ProductImageController::class, 'destroy'])->name('product-images.destroy');
            Route::get('/get-products', 'getProducts')->name('products.getProducts');
        });
















        Route::controller(App\Http\Controllers\Admin\EmployeeCrudController::class)->group(function () {
            // Employee
            Route::get('/employees', 'indexEmployee')->name('employee');
            Route::get('/employees/create', 'createEmployee')->name('createEmployee');
            Route::post('/employees/add', 'storeEmployee')->name('addEmployee');
            Route::get('/employees/verify', 'verifyEmployee')->name('verifyEmployee');
            Route::get('/employees/edit/{id}', 'editEmployee');
            Route::put('/employees/update/{id}', 'updateEmployee');
        });

        //Supplier
        Route::controller(App\Http\Controllers\Admin\SupplierController::class)->group(function () {
            Route::get('/suppliers', 'indexSupplier')->name('supplier');
            Route::post('/suppliers/add', 'storeSupplier')->name('addSupplier');
            Route::get('/suppliers/edit/{id}', 'editSupplier');
            Route::put('/suppliers/update/{id}', 'updateSupplier');
            Route::get('/suppliers/delete/{id}', 'deleteSupplier');
        });

        //Cow
        Route::controller(App\Http\Controllers\Admin\CowController::class)->group(function () {
            Route::get('/cows', 'indexCow')->name('cow');
            // Route::post('/cow/add', 'create');
            Route::get('/cows/create', 'createCow')->name('createCow');
            Route::post('/cows/add', 'storeCow')->name('addCow');

            Route::get('/cows/edit/{cow_id}', 'editCow');
            Route::post('/cows/update/{cow_id}', 'updateCow');
            Route::get('/cows/delete/{id}', 'destroyCow');
        });
    });
});

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
