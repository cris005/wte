<?php

use Illuminate\Support\Facades\Route;

/** Fetch a User's balance of a specific Wallet */
Route::get('/balance/{id}', 'fetchBalance');

/** Fetch a User's balances */
Route::get('/balance', 'fetchAllBalances');

/** Fetch a User's specific Wallet */
Route::get('/{id}', 'fetch');

/** List all wallets that belong to this User */
Route::get('/', 'fetchAll');
