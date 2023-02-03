<?php

namespace App\Http\Requests\V1;

use App\Http\Requests\AbstractTransactionRequest;

class ReversalRequest extends AbstractTransactionRequest
{
    public string $transactionType = 'reverseTransaction';

    /** @inheritDoc */
    public function rules(): array
    {
        return [
            'RefNum' => ['required', 'integer', 'gt:0']
        ];
    }

    /** @inheritDoc */
    public function bodyParameters(): array
    {
        return [
            'RefNum' => [
                'description' => 'The journal record\'s Reference Number',
                'example' => '168742',
            ],
        ];
    }
}
