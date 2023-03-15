<?php

namespace App\Http\Requests\V2\Transaction;

use App\Exceptions\Transaction\UnauthorizedUserException;
use App\Http\Requests\AbstractTransactionRequest;
use Illuminate\Support\Facades\Auth;

class ReversalRequest extends AbstractTransactionRequest
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

        // Must authenticate using Client Credentials Grant
        if (Auth::check()) {
            throw new UnauthorizedUserException();
        }

        return true;
    }
}
