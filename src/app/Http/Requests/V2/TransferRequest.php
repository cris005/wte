<?php

namespace App\Http\Requests\V2;

use App\Http\Requests\AbstractTransactionRequest;

class TransferRequest extends AbstractTransactionRequest
{
    /** @inheritDoc */
    public function rules(): array
    {
        return [
            'transaction_uuid' => ['uuid', 'required', 'exists:transaction,uuid'],
        ];
    }

    /** @inheritDoc */
    public function authorize(): bool
    {
        return $this->transactionBelongsToUser(['uuid' => $this->validated('transaction_uuid')]);
    }
}
