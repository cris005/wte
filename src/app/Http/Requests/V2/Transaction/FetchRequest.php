<?php

namespace App\Http\Requests\V2\Transaction;

use App\Http\Requests\AbstractTransactionRequest;

class FetchRequest extends AbstractTransactionRequest
{
    /** @inheritDoc */
    public function rules(): array
    {
        return [
            'include_fees' => ['nullable', 'boolean'],
            'include_meta' => ['nullable', 'boolean'],
        ];
    }
}
