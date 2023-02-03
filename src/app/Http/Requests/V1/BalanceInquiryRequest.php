<?php

namespace App\Http\Requests\V1;

use App\Http\Requests\AbstractTransactionRequest;

class BalanceInquiryRequest extends AbstractTransactionRequest
{
    public string $transactionType = 'balanceInquiry';

    /** @inheritDoc */
    public function rules(): array
    {
        return [
            'AccountNo' => ['required', 'numeric', 'gt:0', 'max_digits:12']
        ];
    }

    /** @inheritDoc */
    public function bodyParameters(): array
    {
        return [
            'AccountNo' => [
                'description' => 'The Account Number for which the balance is being fetched',
                'example' => '010010586384',
            ],
        ];
    }
}
