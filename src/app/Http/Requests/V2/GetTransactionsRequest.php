<?php

namespace App\Http\Requests\V2;

use App\Http\Requests\AbstractTransactionRequest;

class GetTransactionsRequest extends AbstractTransactionRequest
{
    protected string $failedAuthMessage = 'This User is not allowed to fetch another User\'s transactions.';

    /** @inheritDoc */
    public function rules(): array
    {
        return [
            'transaction_uuid' => ['uuid', 'nullable', 'exists:transaction,uuid'],
            'date_start' => ['date', 'nullable'],
            'date_end' => ['date', 'nullable'],
            'include_debits' => ['bool', 'nullable'],
            'include_credits' => ['bool', 'nullable'],
        ];
    }

    /** @inheritDoc */
    public function authorize(): bool
    {
        $uuid = $this->validated('transaction_uuid');
        if (! empty($uuid)) {
            return $this->transactionBelongsToUser(['uuid' => $uuid]);
        }
        return true;
    }
}
