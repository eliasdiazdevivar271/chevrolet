<?php

use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SiteContentController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/nosotros', [SiteController::class, 'about'])->name('about');
Route::get('/vehiculos', [SiteController::class, 'vehicles'])->name('vehicles.index');
Route::get('/promociones', [SiteController::class, 'promotions'])->name('promotions.index');
Route::get('/contacto', [SiteController::class, 'contact'])->name('contact.show');
Route::post('/contacto', [SiteController::class, 'storeContact'])->middleware('throttle:contact')->name('contact.store');

Route::get('/login', [AuthController::class, 'create'])->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'store'])->middleware(['guest', 'throttle:login'])->name('login.store');
Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth')->name('logout');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'can:admin'])->group(function (): void {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::resource('vehicles', VehicleController::class)->except('show');
    Route::resource('promotions', PromotionController::class)->except('show');
    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('/content', [SiteContentController::class, 'edit'])->name('content.edit');
    Route::put('/content', [SiteContentController::class, 'update'])->name('content.update');
    Route::get('/messages', [ContactMessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{contactMessage}', [ContactMessageController::class, 'show'])->name('messages.show');
});
