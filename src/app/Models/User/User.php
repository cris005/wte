<?php

namespace App\Models\User;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property Account $account
 * @property Wallet $mainWallet
 * @property Collection<Wallet> $wallets
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $connection = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id'                => 'int',
        'account_no'        => 'string',
        'first_name'        => 'string',
        'middle_name'       => 'string',
        'last_name'         => 'string',
        'phone'             => 'string',
        'email'             => 'string',
        'email_verified_at' => 'datetime',
    ];

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
     * Relationship between the User and their main Account record
     *
     * @return HasOne
     */
    public function account(): HasOne
    {
        return $this->hasOne(Account::class, 'ACCOUNT_NO', 'account_no');
    }
}
