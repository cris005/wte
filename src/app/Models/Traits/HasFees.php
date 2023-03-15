<?php

namespace App\Models\Traits;

use App\Models\V2\Transaction\Fee;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property Collection<Fee> $fees The transaction fees
 */
trait HasFees
{
    /**
     * Relationship between a transaction and its fees
     *
     * @return HasMany
     */
    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class, 'transaction_id', 'id');
    }
}
