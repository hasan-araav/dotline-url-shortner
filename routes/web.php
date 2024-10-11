<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UrlController;
use App\Models\Url;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Cache;

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

Route::get('/invalidate-cache/{urlId}', function ($urlId) {
    Cache::forget("basic_analytics:{$urlId}");
    Cache::forget("average_visits:{$urlId}");
    Cache::forget("donut_chart_data:{$urlId}");
});

require __DIR__ . '/auth.php';
