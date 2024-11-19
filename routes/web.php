<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\specialsController;
use App\Http\Controllers\otherServicesController;
use App\Http\Controllers\PaymentController;
use Inertia\Inertia;

// Landing page
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Dashboard
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// Profile management routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Manage Products
Route::middleware('auth')->group(function () {
    Route::get('/products', [ProductsController::class, 'index'])->name('productIndex');
    Route::post('/products', [ProductsController::class, 'store'])->name('products.store');
    Route::put('/products/{id}', [ProductsController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductsController::class, 'destroy'])->name('products.destroy');
    Route::get('/products/search', [ProductsController::class, 'index'])->name('products.search');
});


//manage package
Route::middleware('auth')->group(function () {
    Route::get('/package', [PackageController::class, 'index'])->name('package.index');
    Route::post('/package/store', [PackageController::class, 'store'])->name('package.store');
    Route::delete('/package/{id}', [PackageController::class, 'destroy'])->name('package.destroy');
    Route::put('/package/{id}', [PackageController::class, 'update'])->name('package.update');
});


//manage customer
Route::middleware('auth')->group(function () {
    Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');
    Route::post('/customer/store', [CustomerController::class, 'store'])->name('customer.store');
    Route::delete('/customer/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');
    Route::put('/customer/{id}', [CustomerController::class, 'update'])->name('customer.update');
});

//manage user
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/admin/users', [UserController::class, 'store'])->name('users.store');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('users.update');
});


//manage transaction
Route::middleware('auth')->group(function () {
    Route::get('/transaction', [TransactionController::class, 'index'])->name('transaction.index');
    Route::post('/transaction/store', [TransactionController::class, 'store'])->name('transaction.store');
    Route::delete('/transaction/{id}', [TransactionController::class, 'destroy'])->name('transaction.destroy');
    Route::put('/transaction/{id}', [TransactionController::class, 'update'])->name('transaction.update');
    Route::post('/transaction/add-to-cart', [TransactionController::class, 'addToCart'])->name('transaction.addToCart');
    Route::delete('/transaction/cart/destroy', [TransactionController::class, 'destroyCart'])->name('transaction.destroyCart');
    Route::delete('/transaction/destroy-all', [TransactionController::class, 'destroyAllData'])->name('transaction.destroyAllData');
    Route::post('/customer-details', [TransactionController::class, 'saveCustomerDetails'])->name('customer.details.save');
    Route::post('/cart/add', [TransactionController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/remove', [TransactionController::class, 'removeFromCart'])->name('cart.remove');
});


//transaction_report
Route::middleware('auth')->group(function () {
    Route::get('/transaction-report', [TransactionReportController::class, 'index'])->name('transaction.report');
    Route::get('/transactions/print', [TransactionController::class, 'print'])->name('transactions.print');
    Route::get('/sales-report', [SalesReportController::class, 'index'])->name('sales.report');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // In your web.php or routes file
    Route::put('/transaction/update-status/{id}', [TransactionController::class, 'updateStatus'])->name('transaction.updateStatus');
    Route::get('/transactions/search', [TransactionReportController::class, 'search'])->name('transaction.search');


    //manage wash staff
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::post('/staff/store', [StaffController::class, 'store'])->name('staff.store');
    Route::delete('/staff/{id}', [StaffController::class, 'destroy'])->name('staff.destroy');
    Route::put('/staff/{id}', [StaffController::class, 'update'])->name('staff.update');

    //cmanage category
    Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
    Route::post('/category/store', [CategoryController::class, 'store'])->name('category.store');
    Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
    Route::put('/category/{id}', [CategoryController::class, 'update'])->name('category.update');

    //manage specials
    Route::get('/specials', [specialsController::class, 'index'])->name('specials.index');
    Route::post('/specials/store', [specialsController::class, 'store'])->name('specials.store');
    Route::delete('/specials/{id}', [specialsController::class, 'destroy'])->name('specials.destroy');
    Route::put('/specials/{id}', [specialsController::class, 'update'])->name('specials.update');

    //manage other services
    Route::get('/others', [otherServicesController::class, 'index'])->name('others.index');
    Route::post('/others/store', [otherServicesController::class, 'store'])->name('others.store');
    Route::delete('/others/{id}', [otherServicesController::class, 'destroy'])->name('others.destroy');
    Route::put('/others/{id}', [otherServicesController::class, 'update'])->name('others.update');

    //manage payment
    Route::get('/payment', [TransactionController::class, 'paymentIndex'])->name('payment.index');

    Route::post('/staff/update-status', [StaffController::class, 'updateStatus'])->name('staff.updateStatus');
});






require __DIR__ . '/auth.php';