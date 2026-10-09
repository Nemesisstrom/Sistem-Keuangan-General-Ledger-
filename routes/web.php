<?php

use App\Http\Controllers\AdjustmentController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Reports\BalanceSheetReportController;
use App\Http\Controllers\BankReconciliationController;
use App\Http\Controllers\CashTransactionController;
use App\Http\Controllers\ChartOfAccountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Reports\GeneralLedgerReportController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\Reports\ProfitLossReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// 1. Rute Pengunjung (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// 2. Rute Terproteksi (Wajib Login)
Route::middleware(['auth'])->group(function () {

    // Auth Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Hanya Admin yang bisa mengelola Master Chart of Accounts (CoA) & User
    Route::middleware(['role:Admin'])->group(function () {
        Route::resource('accounts', ChartOfAccountController::class);
        Route::resource('users', UserController::class)->except(['show']);
    });

    // Pemasukan & Pengeluaran Kas Sederhana
    Route::middleware(['permission:create-journal'])->prefix('cash-transactions')->name('cash.')->group(function () {
        Route::get('/in', [CashTransactionController::class, 'createIn'])->name('in');
        Route::post('/in', [CashTransactionController::class, 'storeIn'])->name('in.store');
        Route::get('/out', [CashTransactionController::class, 'createOut'])->name('out');
        Route::post('/out', [CashTransactionController::class, 'storeOut'])->name('out.store');
    });

    // General Journal Entry & Export
    Route::middleware(['permission:create-journal'])->group(function () {
        Route::get('/journals/export/excel', [JournalController::class, 'exportExcel'])->name('journals.export.excel');
        Route::get('/journals/export/pdf', [JournalController::class, 'exportPdf'])->name('journals.export.pdf');

        Route::resource('journals', JournalController::class)->only([
            'index',
            'create',
            'store',
            'show',
        ]);
    });

    // Posting & Reversal Jurnal
    Route::middleware(['permission:manage-accounts'])->prefix('journals')->name('journals.')->group(function () {
        Route::post('/{journal}/post', [JournalController::class, 'post'])->name('post');
        Route::post('/{journal}/reverse', [JournalController::class, 'reverse'])->name('reverse');
    });

    // --- AKTIVITAS 3: Rekonsiliasi Bank ---
    Route::middleware(['permission:manage-accounts'])->prefix('reconciliations')->name('reconciliations.')->group(function () {
        Route::get('/', [BankReconciliationController::class, 'index'])->name('index');
        Route::post('/process', [BankReconciliationController::class, 'process'])->name('process');
    });

    // --- AKTIVITAS 4: Jurnal Penyesuaian ---
    Route::middleware(['permission:manage-accounts'])->prefix('adjustments')->name('adjustments.')->group(function () {
        Route::get('/', [AdjustmentController::class, 'index'])->name('index');
        Route::post('/depreciation', [AdjustmentController::class, 'runDepreciation'])->name('depreciation');
    });

    // Financial Reports (Memerlukan izin 'view-reports')
    Route::middleware(['permission:view-reports'])->prefix('reports')->name('reports.')->group(function () {
        Route::get('/general-ledger', [GeneralLedgerReportController::class, 'index'])->name('general-ledger');
        Route::get('/profit-loss', [ProfitLossReportController::class, 'index'])->name('profit-loss');
        Route::get('/balance-sheet', [BalanceSheetReportController::class, 'index'])->name('balance-sheet');
    });

});
