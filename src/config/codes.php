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
        'internal'             => (int) env('ERR_INTERNAL_EXCEPTION'),
        'account_not_found'    => (int) env('ERR_ACCOUNT_NOT_FOUND'),
        'insufficient_balance' => (int) env('ERR_INSUFFICIENT_BALANCE'),
        'invalid_fees'         => (int) env('ERR_INVALID_FEES'),
        'invalid_ref_no'       => (int) env('ERR_INVALID_REF_NO'),
        'unauthorized'         => (int) env('ERR_ACTION_UNAUTHORIZED'),
    ],

    'status' => [
        'success' => 0,
        'failed'  => 2,
    ],
];
