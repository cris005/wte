<?php

namespace App\Http\Requests\V2;

use App\Http\Requests\AbstractTransactionRequest;
use Illuminate\Support\Facades\Auth;

class BalanceRequest extends AbstractTransactionRequest
{
    protected string $failedAuthMessage = 'Unauthorized to fetch a User\'s balance';

    /** @inheritDoc */
    public function authorize(): bool
    {
        return Auth::check();
    }
}
