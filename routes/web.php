<?php

use App\Http\Controllers\AuctionController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\NotificationController;
// use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use Illuminate\Support\Facades\Route;

// Javne rute — svi mogu vidjeti
Route::get('/', [AuctionController::class, 'index'])->name('home');
Route::get('/auctions', [AuctionController::class, 'index'])->name('auctions.index');
Route::get('/auctions/{auction}', [AuctionController::class, 'show'])->name('auctions.show');
Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');

// Zaštićene rute — mora biti ulogovan i verifikovan email
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Nekretnine — samo prodavac
    Route::resource('properties', PropertyController::class)
        ->except(['index', 'show']);

    // Aukcije — samo prodavac kreira
    Route::resource('auctions', AuctionController::class)
        ->except(['index', 'show']);

    // Licitiranje
    Route::post('/auctions/{auction}/bid', [BidController::class, 'store'])->name('bids.store');
    Route::post('/auctions/{auction}/buynow', [BidController::class, 'buyNow'])->name('bids.buynow');

    // Favoriti
    Route::post('/favorites/{property}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

    // Notifikacije
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');

    // Profil (Breeze generiše)
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
