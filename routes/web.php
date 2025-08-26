<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\MonthController;

Route::redirect('/', '/login');

Route::middleware('auth')->group(function () {
  Route::view('/dashboard', 'dashboard')->name('dashboard'); // temporário
  Route::view('/settings', 'settings')->name('settings'); // temporário

  Route::post('/update-month', MonthController::class)->name('month.update');

  Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});