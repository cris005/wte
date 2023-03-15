<?php

namespace App\Models\Traits;

use App\Models\V2\Transaction\Metadata;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property Collection<Metadata> $meta The transaction fees
 */
trait HasMetadata
{
    /**
     * Relationship between a transaction and its fees
     *
     * @return HasMany
     */
    public function meta(): HasMany
    {
        return $this->hasMany(Metadata::class, 'transaction_id', 'id');
    }
}
