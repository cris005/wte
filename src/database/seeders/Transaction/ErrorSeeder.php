<?php

namespace Database\Seeders\Transaction;

use Database\Seeders\AbstractSeeder;

class ErrorSeeder extends AbstractSeeder
{
    protected string $model = \App\Models\V2\Transaction\Error::class;
    protected array $rows = [
        ['id' => 0,   'name' => 'ERR_NONE', 'description' => 'No error'],
        ['id' => 100, 'name' => 'ERR_LOGIN_FAILED', 'description' => 'Authentication failed'],
        ['id' => 101, 'name' => 'ERR_ACCOUNT_BLOCKED', 'description' => 'Your account has been locked due to suspicious activity'],
        ['id' => 102, 'name' => 'ERR_INVALID_CREDENTIALS', 'description' => 'Could not authenticate using this credentials'],
        ['id' => 200, 'name' => 'ERR_JWT_INVALID', 'description' => 'JWT is malformed or invalid'],
        ['id' => 201, 'name' => 'ERR_JWT_SIGNATURE_INVALID', 'description' => 'Authorization signature is invalid'],
        ['id' => 202, 'name' => 'ERR_JWT_API_KEY_INVALID', 'description' => 'The API key provided is invalid'],
        ['id' => 300, 'name' => 'ERR_INVALID_PARAMETER', 'description' => 'Invalid or missing parameter(s)'],
        ['id' => 301, 'name' => 'ERR_AMOUNT_NOT_ALLOWED', 'description' => 'Amount is out of range'],
        ['id' => 302, 'name' => 'ERR_DUPLICATE_REFERENCE_NUMBER', 'description' => 'Duplicate reference number provided'],
        ['id' => 303, 'name' => 'ERR_TARGET_ACCOUNT_NOT_FOUND', 'description' => 'The account number could not be found'],
        ['id' => 304, 'name' => 'ERR_INVALID_REFERENCE_NUMBER', 'description' => 'The reference number provided is invalid'],
        ['id' => 305, 'name' => 'ERR_ACCESS_DENIED', 'description' => 'Insufficient permissions to access this resource'],
    ];
}
