<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\MonthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\DataManagementController;

Route::redirect('/', '/login');

Route::middleware('auth')->group(function () {
  Route::get('/dashboard', DashboardController::class)->name('dashboard');
  Route::get('/settings', SettingsController::class)->name('settings');
  Route::delete('/account', [AccountController::class, 'destroy'])->name('account.destroy')->middleware(['password.confirm']);

  Route::post('/update-month', MonthController::class)->name('month.update');

  // Rotas de Gerenciamento de Dados
  Route::get('/data/export', [DataManagementController::class, 'export'])->name('data.export');
  Route::post('/data/import', [DataManagementController::class, 'import'])->name('data.import');

  Route::resource('categories', CategoryController::class)->except(['show']);

  // Rotas de Transaçõesc
  Route::get('/incomes', [TransactionController::class, 'index'])->name('incomes.index')->defaults('type', 'income');
  Route::get('/expenses', [TransactionController::class, 'index'])->name('expenses.index')->defaults('type', 'expense');
  Route::get('/transactions/create/{type?}', [TransactionController::class, 'create'])->name('transactions.create');
  Route::resource('transactions', TransactionController::class)->except(['index', 'create', 'show']);

  Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
