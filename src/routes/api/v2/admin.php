<?php

use App\Http\Controllers\V2\TransactionController;
use App\Http\Controllers\V2\WalletController;
use Illuminate\Support\Facades\Route;

Route::prefix('transaction')
    ->controller(TransactionController::class)
    ->group(function () {
        /** Reverse a fund transfer */
        Route::post('/reverse', 'reverse');

        /** Modify a Transaction Record of the current User */
        Route::patch('/{uuid}', 'update');

        /** Create a new Transaction Record for a User */
        Route::post('/', 'create');
    });

Route::prefix('wallet')
    ->controller(WalletController::class)
    ->group(function () {
        /** Create a Wallet */
        Route::post('/', 'create');
    });
