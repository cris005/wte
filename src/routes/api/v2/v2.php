<?php

use App\Http\Controllers\V2\TransactionController;
use App\Http\Controllers\V2\WalletController;
use Illuminate\Support\Facades\Route;

Route::prefix('wallet')
    ->middleware('auth.api')
    ->controller(WalletController::class)
    ->group(base_path('routes/api/v2/wallet.php'));

Route::prefix('transaction')
    ->middleware('auth.api')
    ->controller(TransactionController::class)
    ->group(base_path('routes/api/v2/transaction.php'));
