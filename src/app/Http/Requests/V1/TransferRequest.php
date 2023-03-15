<?php

namespace App\Http\Requests\V1;

use App\Http\Requests\AbstractTransactionRequest;

class TransferRequest extends AbstractTransactionRequest
{
    public string $transactionType = 'transfer';

    /** @inheritDoc */
    public function rules(): array
    {
        return [
            'Source'     => ['required', 'numeric', 'gt:0', 'max_digits:12'],
            'Target'     => ['required', 'numeric', 'gt:0', 'max_digits:12'],
            'Amount'     => ['required', 'numeric', 'gt:0'],
            'Fee'        => ['nullable', 'numeric', 'gte:0'],
            'Accounts'   => ['nullable', 'array', 'min:1'],
            'Accounts.*' => ['required', 'numeric', 'gte:0'],
        ];
    }

    /** @inheritDoc */
    public function bodyParameters(): array
    {
        return [
            'Source' => [
                'description' => 'The source Account Number',
                'example' => '010010586384',
            ],
            'Target' => [
                'description' => 'The target Account Number',
                'example' => '010010645288',
            ],
            'Amount' => [
                'description' => 'The amount to be transferred',
                'example' => '245.50',
            ],
            'Fee' => [
                'description' => 'The transaction\'s total fee (will be sent to the default Fee Account). If there are multiple fee recipients, leave this blank and fill out the "Accounts" parameter.',
                'example' => '10',
            ],
            'Accounts.*' => [
                'description' => 'The Fee Accounts and respective fees for each account. Example: {"0060001": 15.2, "0070001": 12.8}',
                'example' => '0060001',
            ],
        ];
    }
}
