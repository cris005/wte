<?php

namespace App\Models\User;

use App\Exceptions\Wallet\AccountNotFoundException;
use App\Exceptions\Wallet\InsufficientBalanceException;
use App\Models\AbstractModel;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;
use Carbon\Carbon;

/**
 * @property int    $ACCOUNT_ID
 * @property int    $API_USER_ID
 * @property int    $CLIENT_ID
 * @property string $ACCOUNT_NO
 * @property int    $PRODUCT_ID
 * @property string $BALANCE           The Wallet's balance amount
 * @property int    $STATUS_ID
 * @property int    $NORMAL_BALANCE
 * @property int    $BASE_CURRENCY
 * @property Carbon $DATETIME_CREATED
 * @property Carbon $DATETIME_MODIFIED
 */
abstract class AbstractWallet extends AbstractModel
{
    public const CREATED_AT = 'DATETIME_CREATED';
    public const UPDATED_AT = 'DATETIME_MODIFIED';

    /** @var int Allow positive balance only */
    public const BALANCE_ALLOW_POSITIVE_ONLY = 1;
    /** @var int Allow positive or negative balances */
    public const BALANCE_ALLOW_NEGATIVE = 2;

    protected $connection = 'app';
    protected $table = 'WACCOUNTS';
    protected $primaryKey = 'ACCOUNT_ID';

    protected $fillable = [
        'CLIENT_ID',
        'ACCOUNT_NO',
        'PRODUCT_ID',
        'BALANCE',
        'STATUS_ID',
        'NORMAL_BALANCE',
        'BASE_CURRENCY',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'API_USER_ID'       => 'integer',
        'ACCOUNT_ID'        => 'integer',
        'ACCOUNT_NO'        => 'string',
        'NORMAL_BALANCE'    => 'integer',
        'BALANCE'           => 'string',
        'BASE_CURRENCY'     => 'integer',
        'STATUS_ID'         => 'integer',
        'PRODUCT_ID'        => 'integer',
        'DATETIME_CREATED'  => 'datetime',
        'DATETIME_MODIFIED' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'laravel_through_key'
    ];

    /**
     * Fetch a Wallet record
     *
     * @param string $accountNo
     * @return Wallet
     * @throws AccountNotFoundException
     */
    abstract public static function fetch(string $accountNo): static;

    /**
     * Evaluate whether a Debit can be performed or not
     *
     * @param Money $amount
     * @return bool
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     */
    abstract public function canDebit(Money $amount): bool;

    /**
     * Debit funds from the Wallet's balance
     *
     * @param Money $amount
     * @return static
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     * @throws InsufficientBalanceException When not enough balance to perform transaction
     */
    abstract public function debit(Money $amount): static;

    /**
     * Credit funds from the Wallet's balance
     *
     * @param Money $amount
     * @return static
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     */
    abstract public function credit(Money $amount): static;
}
