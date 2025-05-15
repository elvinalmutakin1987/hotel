<?php

use App\Http\Controllers\AdditionalitemController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CleaningController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoodreceiptController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\HousekeepingController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomtypeController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockoutController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
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

Route::middleware(['guest'])->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'store'])->name('login.store');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    Route::group(['middleware' => ['role_or_permission:admin|staff']], function () {
        Route::resource('staff', StaffController::class)->names('staff');
    });

    Route::group(['middleware' => ['role_or_permission:admin|role']], function () {
        Route::resource('role', RoleController::class)->names('role');
    });

    Route::group(['middleware' => ['role_or_permission:admin|user']], function () {
        Route::resource('user', UserController::class)->names('user');
    });

    Route::group(['middleware' => ['role_or_permission:admin|roomtypes']], function () {
        Route::resource('room-types', RoomtypeController::class)->names('room-types');
    });

    Route::group(['middleware' => ['role_or_permission:admin|rooms']], function () {
        Route::resource('rooms', RoomController::class)->names('rooms');
    });

    Route::group(['middleware' => ['role_or_permission:admin|guests']], function () {
        Route::resource('guests', GuestController::class)->names('guests');
    });

    Route::group(['middleware' => ['role_or_permission:admin|additionalitems']], function () {
        Route::resource('additional-items', AdditionalitemController::class)->names('additional-items');
    });

    Route::group(['middleware' => ['role_or_permission:admin|reservations']], function () {
        Route::resource('reservations', ReservationController::class)->names('reservations');

        Route::get('reservations-get-rooms', [ReservationController::class, 'get_rooms'])->name('reservations.get_rooms');
        Route::get('reservations-get-room-by-id', [ReservationController::class, 'get_room_by_id'])->name('reservations.get_room_by_id');
        Route::put('reservations-confirm/{reservation}', [ReservationController::class, 'confirm'])->name('reservations.confirm');
        Route::put('reservations-cancel/{reservation}', [ReservationController::class, 'cancel'])->name('reservations.cancel');
        Route::put('reservations-check-in/{reservation}', [ReservationController::class, 'check_in'])->name('reservations.check_in');
    });

    Route::group(['middleware' => ['role_or_permission:admin|checkin']], function () {
        Route::resource('check-in', CheckinController::class)->names('checkin');
    });

    Route::group(['middleware' => ['role_or_permission:admin|checkout']], function () {
        Route::resource('check-out', CheckoutController::class)->names('checkout');
    });

    Route::group(['middleware' => ['role_or_permission:admin|cleaning']], function () {
        Route::resource('cleaning', CleaningController::class)->names('cleaning');
    });

    Route::group(['middleware' => ['role_or_permission:admin|supplier']], function () {
        Route::resource('supplier', SupplierController::class)->names('supplier');
    });

    Route::group(['middleware' => ['role_or_permission:admin|purchase']], function () {
        Route::resource('purchase', PurchaseController::class)->names('purchase');

        Route::get('purchase/{purchase}/print', [PurchaseController::class, 'print'])->name('purchase.print');
        Route::get('purchase-get-item', [PurchaseController::class, 'get_item'])->name('purchase.get_item');
        Route::get('purchase-get-item-by-id', [PurchaseController::class, 'get_item_by_id'])->name('purchase.get_item_by_id');
    });

    Route::group(['middleware' => ['role_or_permission:admin|stocks']], function () {
        Route::resource('stocks', StockController::class)->names('stocks');

        Route::get('stocks-get-supplier', [StockController::class, 'get_supplier'])->name('stocks.get_rooms');
    });

    Route::group(['middleware' => ['role_or_permission:admin|goodreceipt']], function () {
        Route::resource('goodreceipt', GoodreceiptController::class)->names('goodreceipt');

        Route::get('goodreceipt/{goodreceipt}/print', [GoodreceiptController::class, 'print'])->name('goodreceipt.print');
        Route::get('goodreceipt-get-purchase', [GoodreceiptController::class, 'get_purchase'])->name('goodreceipt.get_purchase');
        Route::get('goodreceipt-get-item', [GoodreceiptController::class, 'get_item'])->name('goodreceipt.get_item');
        Route::get('goodreceipt-get-item-by-id', [GoodreceiptController::class, 'get_item_by_id'])->name('goodreceipt.get_item_by_id');
    });

    Route::group(['middleware' => ['role_or_permission:admin|stockout']], function () {
        Route::resource('stockout', StockoutController::class)->names('stockout');

        Route::get('stockout/{stockout}/print', [StockoutController::class, 'print'])->name('stockout.print');
        Route::get('stockout-get-item', [StockoutController::class, 'get_item'])->name('stockout.get_item');
        Route::get('stockout-get-item-by-id', [StockoutController::class, 'get_item_by_id'])->name('stockout.get_item_by_id');
    });

    Route::group(['middleware' => ['role_or_permission:admin|invoice']], function () {
        Route::resource('invoice', InvoiceController::class)->names('invoice');

        Route::get('invoice/{invoice}/print', [InvoiceController::class, 'print'])->name('invoice.print');
        Route::get('invoice-get-item', [InvoiceController::class, 'get_item'])->name('invoice.get_item');
        Route::get('invoice-get-item-by-id', [InvoiceController::class, 'get_item_by_id'])->name('invoice.get_item_by_id');
        Route::get('invoice-get-goodreceipt', [InvoiceController::class, 'get_goodreceipt'])->name('invoice.get_goodreceipt');
    });
});
