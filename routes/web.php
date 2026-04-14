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

// Javne rute bez parametara — moraju biti prve
Route::get('/', [AuctionController::class, 'index'])->name('home');
Route::get('/auctions', [AuctionController::class, 'index'])->name('auctions.index');

// Zasticene rute
//verifed nalog!!!
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
});

// Javne rute SA parametrima — moraju biti na dnu!
Route::get('/auctions/{auction}', [AuctionController::class, 'show'])->name('auctions.show');
Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');

require __DIR__ . '/auth.php';
