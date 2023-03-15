<?php

namespace App\Http\Requests\V2\Transaction;

use App\Http\Requests\AbstractTransactionRequest;

class UpdateRequest extends AbstractTransactionRequest
{
    /** @inheritDoc */
    public function rules(): array
    {
        return [
            'status_id'          => ['nullable', 'exists:transaction_status,id'],
            'external_ref_no'    => ['nullable'],
            'remarks'            => ['nullable'],
            'metadata'           => ['nullable', 'array', 'min:1'],
            'metadata.*.key'     => ['required', 'string'],
            'metadata.*.value'   => ['required']
        ];
    }
}
