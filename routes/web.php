<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;

// Главная страница — редирект на dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Панель управления (Dashboard)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('categories', CategoryController::class);
    Route::get('categories/trashed', [CategoryController::class, 'trashed'])->name('categories.trashed');
    Route::post('categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::delete('categories/{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('categories.forceDelete');

    Route::resource('transactions', TransactionController::class);
    Route::get('transactions/trashed', [TransactionController::class, 'trashed'])->name('transactions.trashed');
    Route::post('transactions/{id}/restore', [TransactionController::class, 'restore'])->name('transactions.restore');
    Route::delete('transactions/{id}/force-delete', [TransactionController::class, 'forceDelete'])->name('transactions.forceDelete');
    Route::get('transactions/report', [TransactionController::class, 'report'])
    ->middleware('auth')
    ->name('transactions.report');
    Route::get('transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');



    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index'); 
    Route::post('/reports', [ReportController::class, 'generate'])->name('reports.generate'); 
    Route::post('/reports/pdf', [ReportController::class, 'generatePdf'])->name('reports.generatePdf'); 
    Route::post('/reports/send', [ReportController::class, 'sendReport'])->name('reports.send'); 
    Route::post('/reports/send-email', [ReportController::class,'sendEmail'])->name('reports.sendEmail');
});

// Подключаем маршруты аутентификации
require __DIR__.'/auth.php';
