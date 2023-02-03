<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property string $ACCOUNT_NO Account No. of the owner of this Portfolio
 * @property string $BACCOUNT_NO Account No. of the Wallet that belongs to this Portfolio
 * @property Wallet $wallet
 */
class Portfolio extends Model
{
    protected $connection = 'app';
    protected $table = 'BACCOUNT';
    protected $primaryKey = 'ACCOUNT_ID';

    /**
     * Relationship between this Model and a Wallet record
     *
     * @return HasOne
     */
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class, 'ACCOUNT_NO', 'BACCOUNT_NO');
    }
}
