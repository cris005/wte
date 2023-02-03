<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Internal Error and Status Codes
    |--------------------------------------------------------------------------
    |
    | This file is for storing of all internal codes that relate to errors
    | or statuses.
    |
    */

    'error' => [
        'internal'             => env('ERR_INTERNAL_EXCEPTION'),
        'account_not_found'    => env('ERR_ACCOUNT_NOT_FOUND'),
        'insufficient_balance' => env('ERR_INSUFFICIENT_BALANCE'),
        'invalid_fees'         => env('ERR_INVALID_FEES'),
        'invalid_ref_no'       => env('ERR_INVALID_REF_NO'),
        'unauthorized'         => env('ERR_ACTION_UNAUTHORIZED'),
    ],

    'status' => [
        'success' => 0,
        'failed'  => 2,
    ],
];
