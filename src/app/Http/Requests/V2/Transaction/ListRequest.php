<?php

namespace App\Http\Requests\V2\Transaction;

use App\Http\Requests\AbstractTransactionRequest;
use App\Models\V2\Transaction\Transaction;

class ListRequest extends AbstractTransactionRequest
{
    /** @var array|array[] */
    public array $params;

    /** @inheritDoc */
    public function rules(): array
    {
        return [
            // TODO: add more filters specific to Transactions
            'date_start' => ['date', 'nullable'],
            'date_end' => ['date', 'nullable'],
        ];
    }

    /** @inheritDoc */
    public function authorize(): bool
    {
        $this->params = [
            ['created_at', '>=', $this->validated('date_start', '2000-01-01')],
            ['created_at', '<=', $this->validated('date_start', date('Y-m-d'))],
        ];
        return true;
    }
}
