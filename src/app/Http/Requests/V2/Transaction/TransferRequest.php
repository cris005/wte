<?php

namespace App\Http\Requests\V2\Transaction;

use App\Exceptions\Transaction\InvalidTransferException;
use App\Exceptions\Transaction\UnauthorizedUserException;
use App\Http\Requests\AbstractTransactionRequest;

class TransferRequest extends AbstractTransactionRequest
{
    /** @inheritDoc */
    public function rules(): array
    {
        return [
            'transaction_uuid' => ['uuid', 'required'],
        ];
    }

    /** @inheritDoc */
    public function authorize(): bool
    {
        $this->findTransaction();

        if (! $this->transactionBelongsToUser($this->transaction)) {
            throw new UnauthorizedUserException();
        }

        // Reject if transaction is not pending for processing
        if ($this->transaction->status_id !== 3) {
            throw new InvalidTransferException();
        }

        return true;
    }
}
