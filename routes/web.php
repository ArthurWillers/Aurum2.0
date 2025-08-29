<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\MonthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;

Route::redirect('/', '/login');

Route::middleware('auth')->group(function () {
  Route::get('/dashboard', DashboardController::class)->name('dashboard');
  Route::view('/settings', 'settings')->name('settings'); // temporário

  Route::post('/update-month', MonthController::class)->name('month.update');

  Route::resource('categories', CategoryController::class)->except(['show']);

  Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});