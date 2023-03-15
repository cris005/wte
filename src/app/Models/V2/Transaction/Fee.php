<?php

namespace App\Models\V2\Transaction;

use App\Models\AbstractModel;
use Illuminate\Support\Str;

class Fee extends AbstractModel
{
    protected $table = 'transaction_fee';

    protected $fillable = [
        'transaction_id',
        'type_id',
        'account_id',
        'amount',
    ];

    /** @inheritDoc */
    public static function boot(): void
    {
        parent::boot();

        static::creating(static function (Fee $fee) {
            $fee->uuid = Str::orderedUuid()->toString();
        });
    }
}
