<?php

use Illuminate\Support\Facades\Route;

/** Execute a fund transfer */
Route::post('/transfer', 'transfer');

/** Fetch Transaction Records from a User's Wallet */
Route::get('/wallet/{id}', 'fetchFromWallet');

/** Fetch a specific Transaction Record of the current User */
Route::get('/{uuid}', 'fetch');

/** Fetch and filter the Transaction Records of the current User */
Route::get('/', 'fetchAll');

/** Create a new Transaction Record for the current User */
Route::post('/', 'create');
