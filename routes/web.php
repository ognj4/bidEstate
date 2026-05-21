<?php

use App\Http\Controllers\AuctionController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PropertyController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

Route::get('/', [AuctionController::class, 'index'])->name('home');
Route::get('/auctions', [AuctionController::class, 'index'])->name('auctions.index');

Route::middleware(['auth'])->group(function () {

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('properties', PropertyController::class)
        ->except(['index', 'show']);

    Route::resource('auctions', AuctionController::class)
        ->except(['index', 'show']);

    Route::post('/auctions/{auction}/bid', [BidController::class, 'store'])->name('bids.store');
    Route::post('/auctions/{auction}/buynow', [BidController::class, 'buyNow'])->name('bids.buynow');

    Route::post('/favorites/{property}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');

    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');


    // Admin rute
    Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminController::class, 'index'])->name('dashboard');
        Route::get('/users', [\App\Http\Controllers\Admin\AdminUserController::class, 'index'])->name('users');
        Route::patch('/users/{user}/role', [\App\Http\Controllers\Admin\AdminUserController::class, 'updateRole'])->name('users.role');
        Route::delete('/users/{user}', [\App\Http\Controllers\Admin\AdminUserController::class, 'destroy'])->name('users.destroy');
        Route::get('/properties', [\App\Http\Controllers\Admin\AdminPropertyController::class, 'index'])->name('properties');
        Route::delete('/properties/{property}', [\App\Http\Controllers\Admin\AdminPropertyController::class, 'destroy'])->name('properties.destroy');
        Route::get('/auctions', [\App\Http\Controllers\Admin\AdminAuctionController::class, 'index'])->name('auctions');
        Route::patch('/auctions/{auction}/cancel', [\App\Http\Controllers\Admin\AdminAuctionController::class, 'cancel'])->name('auctions.cancel');
    });
});

Route::get('/auctions/{auction}', [AuctionController::class, 'show'])->name('auctions.show');
Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');

require __DIR__ . '/auth.php';
