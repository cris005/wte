<?php

namespace App\Models\Traits;

use App\Models\User\Account;
use App\Models\User\Portfolio;
use App\Models\User\Wallet;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property Account $account The User's account
 * @property Collection<Portfolio> $portfolio The User's portfolio of wallet accounts
 * @property Wallet $mainWallet The User's main wallet
 * @property Collection<Wallet> $wallets The User's list of wallets
 */
trait HasWallets
{
    /**
     * Relationship between the User and their main Wallet record
     *
     * @return HasOne
     */
    public function mainWallet(): HasOne
    {
        return $this->hasOne(Wallet::class, 'ACCOUNT_NO', 'account_no');
    }

    /**
     * Relationship between the User and multiple Wallet records
     *
     * @return HasManyThrough
     */
    public function wallets(): HasManyThrough
    {
        return $this->hasManyThrough(
            Wallet::class,
            Portfolio::class,
            'ACCOUNT_NO',
            'ACCOUNT_NO',
            'account_no',
            'BACCOUNT_NO'
        );
    }

    /**
     * Relationship between the User and their Portfolio of Wallet Accounts
     *
     * @return HasMany
     */
    public function portfolio(): HasMany
    {
        return $this->hasMany(Portfolio::class, 'ACCOUNT_NO', 'account_no');
    }

    /**
     * Relationship between the User and their main Account record
     *
     * @return HasOne
     */
    public function account(): HasOne
    {
        return $this->hasOne(Account::class, 'ACCOUNT_NO', 'account_no');
    }
}
