<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UrlController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UrlController::class, 'index'])->name('index');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UrlController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/t/{shortCode}', [UrlController::class, 'redirect']);
Route::post('/shorten', [UrlController::class, 'shorten'])->name('url.shorten');
Route::get('/analytics/{shortCode}', [UrlController::class, 'getAnalytics'])->name('url.analytics');
Route::delete('/{shortCode}', [UrlController::class, 'destroy'])->name('url.delete');

require __DIR__ . '/auth.php';
