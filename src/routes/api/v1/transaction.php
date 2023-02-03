<?php

use Illuminate\Support\Facades\Route;

/** Execute a fund transfer */
Route::post('/transfer', 'transfer');

/** Reverse a fund transfer (if current user is the recipient) */
Route::post('/reverseTransaction', 'reverse');

/** Fetch the balance of an Account */
Route::post('/balanceinquiry', 'balanceInquiry');
