<?php

use App\Http\Controllers\V2\JournalController;
use App\Http\Controllers\V2\TransactionController;
use App\Http\Controllers\V2\WalletController;
use Illuminate\Support\Facades\Route;

Route::prefix('wallet')
    ->controller(WalletController::class)
    ->group(base_path('routes/api/v2/wallet.php'));

Route::prefix('transaction')
    ->controller(TransactionController::class)
    ->group(base_path('routes/api/v2/transaction.php'));

Route::prefix('journal')
    ->controller(JournalController::class)
    ->group(base_path('routes/api/v2/journal.php'));

Route::prefix('admin')
    ->withoutMiddleware('auth.api')
    ->middleware('auth.api.server')
    ->group(base_path('routes/api/v2/admin.php'));
