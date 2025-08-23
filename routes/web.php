<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

Route::redirect('/', '/login');

Route::middleware('auth')->group(function () {
  Route::view('/dashboard', 'dashboard')->name('dashboard'); // temporário

  Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout'); //temporário
});