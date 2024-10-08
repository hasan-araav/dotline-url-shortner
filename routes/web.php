<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UrlController;
use Illuminate\Support\Facades\Route;
use App\Models\Url;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard')->with(['urls' => Url::all()]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/shorten', [UrlController::class, 'shorten'])->name('url.shorten');
    Route::get('/analytics/{shortCode}', [UrlController::class, 'getAnalytics'])->name('url.analytics');
    Route::delete('/{shortCode}', [UrlController::class, 'destroy'])->name('url.delete');
});

Route::get('/{shortCode}', [UrlController::class, 'redirect']);

require __DIR__.'/auth.php';
