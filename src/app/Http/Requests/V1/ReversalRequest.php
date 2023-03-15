<?php

namespace App\Http\Requests\V1;

use App\Exceptions\Journal\InvalidReversalException;
use App\Http\Requests\AbstractTransactionRequest;
use App\Models\V1\Journal;
use Illuminate\Database\Eloquent\Collection;

class ReversalRequest extends AbstractTransactionRequest
{
    public string $transactionType = 'reverseTransaction';

    /** @var Collection<Journal> List of transactions associated with the provided Ref. No. */
    public Collection $transactions;

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

    /** @inheritDoc */
    public function authorize(): bool
    {
        $refNo = $this->validated('RefNum');
        $isAuthorized = Journal::canReverse($refNo);
        $this->transactions = Journal::fetch($refNo);
        return $isAuthorized;
    }

    /** @inheritDoc */
    public function failedAuthorization(): never
    {
        throw new InvalidReversalException();
    }
}
