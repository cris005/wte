<?php

/** Fetch all Journal Entries of a specific Transaction Record of the current User */
Route::get('/transaction/{uuid}', 'fetchFromTransaction');

/** Fetch and filter all Journal Entries of a Wallet from the current User */
Route::get('/wallet/{id}', 'fetchFromWallet');

/** Fetch a specific Journal Entry of the current User */
Route::get('/{uuid}', 'fetch');

/** Fetch and filter all Journal Entries of the current User */
Route::get('/', 'fetchAll');
