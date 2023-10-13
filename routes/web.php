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
// Route::get('/email', function(){
//     orderEmail(14);
// });
Route::controller(App\Http\Controllers\CartController::class)->group(function () {
    Route::get('/cart', 'cart')->name('front.cart');
    Route::post('/add-to-cart', 'addToCart')->name('front.addToCart');
    Route::post('/update-cart', 'updateCart')->name('front.updateCart');
    Route::post('/delete-cart', 'deleteItem')->name('front.deleteItem.cart');
    Route::get('/checkout', 'checkout')->name('front.checkout');
    Route::post('/process-checkout', 'processCheckout')->name('front.processCheckout');
    Route::get('/thanks/{orderId}', 'thankyou')->name('front.thankyou');
    Route::post('/get-order-summery', 'getOrderSummery')->name('front.getOrderSummery');
    Route::post('/apply-discount', 'applyDiscount')->name('front.applyDiscount');
    Route::post('/remove-discount', 'removeDiscount')->name('front.removeDiscount');
    Route::post('/add-to-wishlist', [FrontController::class, 'addToWishlist'])->name('front.addToWishlist');
    Route::get('/page/{slug}', [FrontController::class, 'page'])->name('front.page');
    Route::post('/send-contact-email', [FrontController::class, 'sendContactEmail'])->name('front.sendContactEmail');
});

// Route::group(['prefix' => 'account'], function () {
    Route::controller(App\Http\Controllers\AuthController::class)->group(function () {
        Route::group(['middleware' => 'guest'], function () {
            Route::get('/login', 'login')->name('account.login');
            Route::post('/login', 'authenticate')->name('account.authenticate');

            Route::get('/register', 'register')->name('account.register');
            Route::post('/process-register', 'processRegister')->name('account.processRegister');
            Route::get('/verify', 'verifyCustomer')->name('account.verifyCustomer');
            Route::get('/forgot-password', 'forgotPassword')->name('front.forgotPassword');
            Route::post('/process-forgot-password', 'processForgetPassword')->name('front.processForgetPassword');
            Route::get('/reset-password/{token}', 'resetPassword')->name('front.resetPassword');
            Route::post('/process-reset-password', 'processResetPassword')->name('front.processResetPassword');
        });

        Route::middleware(['auth:customer', 'is_customer_verify_email'])->group(function () {
            Route::get('/profile', 'profile')->name('account.profile');
            Route::post('/update-profile', 'updateProfile')->name('account.updateProfile');
            Route::post('/update-address', 'updateAddress')->name('account.updateAddress');
            Route::get('/change-password', 'showChangePasswordForm')->name('account.showChangePasswordForm');
            Route::post('/process-change-password', 'changePassword')->name('account.processChangePassword');
            Route::get('/my-orders', 'orders')->name('account.orders');
            Route::get('/my-wishlists', 'wishlist')->name('account.wishlist');
            Route::post('/remove-product-from-wishlist', 'removeProductFromWishList')->name('account.removeProductFromWishList');
            Route::get('/order-detail/{orderId}', 'orderDetail')->name('account.orderDetail');
            Route::get('/logout', 'logout')->name('account.logout');
        });
    });
// });

Route::get('/xlogin', [LoginController::class, 'index'])->name('login')->middleware('PreventBackHistory');
Route::post('/check', [LoginController::class, 'checkLogin'])->name('checkLogin');
Route::get('/xlogout', [LoginController::class, 'logout'])->name('logoutAll');
// temp-images.create
Route::post('/upload-temp-image', [TempImagesController::class, 'create'])->name('temp-images.create');
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
        Route::view('/admin/login', 'admin.login')->name('login');
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

        Route::get('/getRow', function (Request $request) {
            $row = 0;
            if (!empty($request->row)) {
                $row = $request->row;
            }
            return response()->json([
                'status' => true,
                'row' => $row
            ]);
        })->name('getRow');

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

        // Shipping Routes
        Route::controller(App\Http\Controllers\Admin\ShippingController::class)->group(function () {
            Route::get('/shipping/create', 'create')->name('shipping.create');
            Route::post('/shipping', 'store')->name('shipping.store');
            Route::get('/shipping/edit/{id}', 'edit')->name('shipping.edit');
            Route::put('/shipping/{id}', 'update')->name('shipping.update');
            Route::delete('/shipping/{id}', 'destroy')->name('shipping.delete');
        });

        // Coupon Code Routes
        Route::controller(App\Http\Controllers\Admin\DiscountCodeController::class)->group(function () {
            Route::get('/coupons', 'index')->name('coupons.index');
            Route::get('/coupons/create', 'create')->name('coupons.create');
            Route::post('/coupons', 'store')->name('coupons.store');
            Route::get('/coupons/edit/{id}', 'edit')->name('coupons.edit');
            Route::put('/coupons/{id}', 'update')->name('coupons.update');
            Route::delete('/coupons/{id}', 'destroy')->name('coupons.delete');
        });

        // Order Routes
        Route::controller(App\Http\Controllers\Admin\OrderController::class)->group(function () {
            Route::get('/orders', 'index')->name('orders.index');
            Route::get('/orders/{id}', 'detail')->name('orders.detail');
            Route::post('/order/change-status/{id}', 'changeOrderStatus')->name('orders.changeOrderStatus');
            Route::post('/order/send-email/{id}', 'sendInvoiceEmail')->name('orders.sendInvoiceEmail');
        });

        // Customer Routes
        Route::controller(App\Http\Controllers\Admin\CustomerController::class)->group(function () {
            Route::get('/customers', 'index')->name('customers.index');
            Route::get('/customers/create', 'create')->name('customers.create');
            Route::post('/customers', 'store')->name('customers.store');
            Route::get('/customers/edit/{id}', 'edit')->name('customers.edit');
            Route::put('/customers/{id}', 'update')->name('customers.update');
            Route::delete('/customers/{id}', 'destroy')->name('customers.delete');
        });

        // Page Routes
        Route::controller(App\Http\Controllers\Admin\PageController::class)->group(function () {
            Route::get('/pages', 'index')->name('pages.index');
            Route::get('/pages/create', 'create')->name('pages.create');
            Route::post('/pages', 'store')->name('pages.store');
            Route::get('/pages/edit/{id}', 'edit')->name('pages.edit');
            Route::put('/pages/{id}', 'update')->name('pages.update');
            Route::delete('/pages/{id}', 'destroy')->name('pages.delete');
        });

        // Setting Routes
        Route::controller(App\Http\Controllers\Admin\SettingController::class)->group(function () {
            Route::get('/change-password', 'showChangePasswordForm')->name('showChangePasswordForm');
            Route::post('/process-change-password', 'processChangePassword')->name('processChangePassword');

        });

        // Employee Routes
        Route::controller(App\Http\Controllers\Admin\EmployeeController::class)->group(function () {
            Route::get('/employees', 'index')->name('employees.index');
            Route::view('/employees/create', 'admin.employee.create')->name('employees.create');
            Route::post('/employees', 'store')->name('employees.store');
            Route::get('/employees/edit/{id}', 'edit')->name('employees.edit');
            Route::put('/employees/{id}', 'update')->name('employees.update');
            Route::delete('/employees/{id}', 'destroy')->name('employees.delete');
        });

        // Salary Routes
        Route::controller(App\Http\Controllers\Admin\SalaryController::class)->group(function () {
            Route::get('/salaries', 'index')->name('salaries.index');
            Route::get('/salaries/create', 'create')->name('salaries.create');
            Route::post('/salaries', 'store')->name('salaries.store');
            Route::get('/salaries/edit/{id}', 'edit')->name('salaries.edit');
            Route::put('/salaries/{id}', 'update')->name('salaries.update');
            Route::delete('/salaries/{id}', 'destroy')->name('salaries.delete');
        });

        // Supplier Routes
        Route::controller(App\Http\Controllers\Admin\SupplierController::class)->group(function () {
            Route::get('/suppliers/create', 'create')->name('suppliers.create');
            Route::post('/suppliers', 'store')->name('suppliers.store');
            Route::get('/suppliers/edit/{id}', 'edit')->name('suppliers.edit');
            Route::put('/suppliers/{id}', 'update')->name('suppliers.update');
            Route::delete('/suppliers/{id}', 'destroy')->name('suppliers.delete');
        });

        // Cow Routes
        Route::controller(App\Http\Controllers\Admin\CowController::class)->group(function () {
            Route::get('/cows', 'index')->name('cows.index');
            Route::get('/cows/create', 'create')->name('cows.create');
            Route::post('/cows', 'store')->name('cows.store');
            Route::get('/cows/edit/{id}', 'edit')->name('cows.edit');
            Route::put('/cows/{id}', 'update')->name('cows.update');
            Route::delete('/cows/{id}', 'destroy')->name('cows.delete');
        });

        // Order cow Routes
        Route::controller(App\Http\Controllers\Admin\OrderCowController::class)->group(function () {
            Route::get('/order-cows', 'index')->name('order-cows.index');
            Route::get('/order-cows/create', 'create')->name('order-cows.create');
            Route::post('/order-cows', 'store')->name('order-cows.store');
            Route::get('/order-cows/edit/{id}', 'edit')->name('order-cows.edit');
            Route::put('/order-cows/{id}', 'update')->name('order-cows.update');
            Route::delete('/order-cows/{id}', 'destroy')->name('order-cows.delete');
        });










        Route::controller(App\Http\Controllers\Admin\EmployeeCrudController::class)->group(function () {
            // Employee
            // Route::get('/employees', 'indexEmployee')->name('employee');
            // Route::get('/employees/create', 'createEmployee')->name('createEmployee');
            // Route::post('/employees/add', 'storeEmployee')->name('addEmployee');
            // Route::get('/employees/verify', 'verifyEmployee')->name('verifyEmployee');
            // Route::get('/employees/edit/{id}', 'editEmployee');
            // Route::put('/employees/update/{id}', 'updateEmployee');
        });

        //Supplier
        // Route::controller(App\Http\Controllers\Admin\SupplierController::class)->group(function () {
        //     Route::get('/suppliers', 'indexSupplier')->name('supplier');
        //     Route::post('/suppliers/add', 'storeSupplier')->name('addSupplier');
        //     Route::get('/suppliers/edit/{id}', 'editSupplier');
        //     Route::put('/suppliers/update/{id}', 'updateSupplier');
        //     Route::get('/suppliers/delete/{id}', 'deleteSupplier');
        // });

        //Cow
        // Route::controller(App\Http\Controllers\Admin\CowController::class)->group(function () {
        //     Route::get('/cows', 'indexCow')->name('cow');
        //     // Route::post('/cow/add', 'create');
        //     Route::get('/cows/create', 'createCow')->name('createCow');
        //     Route::post('/cows/add', 'storeCow')->name('addCow');

        //     Route::get('/cows/edit/{cow_id}', 'editCow');
        //     Route::post('/cows/update/{cow_id}', 'updateCow');
        //     Route::get('/cows/delete/{id}', 'destroyCow');
        // });
    });
});

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
