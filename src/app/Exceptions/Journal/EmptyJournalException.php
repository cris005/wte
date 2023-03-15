<?php

namespace App\Exceptions\Journal;

use App\Exceptions\AbstractWalletException;
use App\Http\Factory\JsonResponseFactory;
use Illuminate\Http\JsonResponse;
use Throwable;

class EmptyJournalException extends AbstractWalletException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'This transaction cannot be processed as it contains no credit/debit journal entries',
            config('codes.error.unauthorized'),
            $previous
        );
    }
}
