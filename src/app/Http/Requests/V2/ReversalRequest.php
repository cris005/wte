<?php

namespace App\Http\Requests\V2;

use App\Http\Requests\AbstractTransactionRequest;
use Illuminate\Support\Facades\Auth;

class ReversalRequest extends AbstractTransactionRequest
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
        return ! Auth::check(); // Cannot be performed by a regular User
    }
}
