<?php

use Illuminate\Support\Facades\Route;

/** Execute a fund transfer */
Route::post('/transfer', 'transfer');

/** Reverse a fund transfer (if current user is the recipient) */
Route::post('/reverse', 'reverse');

/** Fetch and/or filter the Wallet Journal Records of the current User */
Route::get('/', 'fetch');
