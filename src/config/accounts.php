<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Wallet Accounts
    |--------------------------------------------------------------------------
    |
    | This file is for storing of Account numbers to be used in this application
    | such as Settlement or Fee Accounts.
    |
    */

    'fees' => [
        'default' => env('WALLET_DEFAULT_FEE_ACCOUNT'),
    ],
];
