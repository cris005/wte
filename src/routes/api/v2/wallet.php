<?php

use Illuminate\Support\Facades\Route;

/** Fetch a User's balance(s) */
Route::get('/balance/{account_no?}', 'fetchBalance');

/** Fetch a specific Wallet's details */
Route::get('/{account_no}', 'fetch');

/** List all wallets that belong to this User */
Route::get('/', 'fetchAll');
