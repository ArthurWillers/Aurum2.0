<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\MonthController;
use App\Http\Controllers\DashboardController;

Route::redirect('/', '/login');

Route::middleware('auth')->group(function () {
  Route::get('/dashboard', DashboardController::class)->name('dashboard');
  Route::view('/settings', 'settings')->name('settings'); // temporário

  Route::post('/update-month', MonthController::class)->name('month.update');

  Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});