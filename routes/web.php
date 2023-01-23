<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LogoutController;

use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LotteryController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\SettingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('login');
// LOGIN
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
// REGISTER
Route::get('/register', [RegisterController::class, 'index']);
Route::post('/register', [RegisterController::class, 'register']);
// LOGOUT
Route::get('/logout', [LogoutController::class, 'logout']);

// API Routes
Route::group(['middleware' => 'auth'], function () {
    // SUPPLIERS
    Route::resource('/suppliers', SupplierController::class);
    // LOTTERIES
    Route::resource('/lotteries', LotteryController::class);
    // CUSTOMERS
    Route::get('/customers/give', [CustomerController::class, 'give']);
    Route::post('/customers/give', [CustomerController::class, 'give_store']);
    Route::get('/customers/return', [CustomerController::class, 'return']);
    Route::post('/customers/return', [CustomerController::class, 'return_store']);
    Route::get('/customers/pay', [CustomerController::class, 'pay']);
    Route::post('/customers/pay', [CustomerController::class, 'pay_store']);
    Route::resource('/customers', CustomerController::class);
    // TICKETS
    Route::resource('/tickets', TicketController::class);
    // ENTIRES
    Route::resource('/entires', EntireController::class);
    
    // SETTINGS
    Route::resource('/settings', SettingController::class);
});