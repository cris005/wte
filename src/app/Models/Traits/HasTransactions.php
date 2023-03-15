<?php

namespace App\Models\Traits;

use App\Models\V2\Transaction\Transaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property Collection<Transaction> $transactions List of transactions
 */
trait HasTransactions
{
    /**
     * Relationship between the User and their transactions
     *
     * @return HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id', 'id');
    }
}
