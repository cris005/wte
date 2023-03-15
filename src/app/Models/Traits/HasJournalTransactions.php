<?php

namespace App\Models\Traits;

use App\Models\V2\Journal\FeeTransaction;
use App\Models\V2\Journal\FundTransaction;
use App\Models\V2\Journal\JournalTransaction;
use App\Models\V2\Journal\ReversalTransaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property Collection<JournalTransaction> $journalTransactions
 * @property Collection<FeeTransaction> $feeTransactions
 * @property Collection<ReversalTransaction> $reversalTransactions
 * @property Collection<FundTransaction> $fundTransactions
 */
trait HasJournalTransactions
{
    /**
     * Relationship between a transaction and all its debit/credit journal entries
     *
     * @return HasMany
     */
    public function journalTransactions(): HasMany
    {
        return $this->hasMany(JournalTransaction::class, 'transaction_id', 'id');
    }

    /**
     * Relationship between a transaction and its debited fees journal entries
     *
     * @return HasMany
     */
    public function feeTransactions(): HasMany
    {
        return $this->hasMany(FeeTransaction::class, 'transaction_id', 'id');
    }

    /**
     * Relationship between a transaction and its reversed funds journal entries
     *
     * @return HasMany
     */
    public function reversalTransactions(): HasMany
    {
        return $this->hasMany(ReversalTransaction::class, 'transaction_id', 'id');
    }

    /**
     * Relationship between a transaction and its debited funds journal entries
     *
     * @return HasMany
     */
    public function fundTransactions(): HasMany
    {
        return $this->hasMany(FundTransaction::class, 'transaction_id', 'id');
    }
}
