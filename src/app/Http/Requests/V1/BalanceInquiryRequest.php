<?php

namespace App\Http\Requests\V1;

use App\Http\Requests\AbstractTransactionRequest;
use App\Models\User\Wallet;

class BalanceInquiryRequest extends AbstractTransactionRequest
{
    public string $transactionType = 'balanceInquiry';
    public Wallet $wallet;

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

    /** @inheritDoc */
    public function authorize(): bool
    {
        $this->wallet = Wallet::fetch($this->validated('AccountNo'));
        return true;
    }
}
