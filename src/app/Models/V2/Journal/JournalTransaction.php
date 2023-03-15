<?php

namespace App\Models\V2\Journal;

use App\Models\AbstractModel;
use App\Models\User\Wallet;
use Brick\Money\Money;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

/**
 * @property int          $id
 * @property string       $uuid
 * @property int          $transaction_id
 * @property string       $transaction_ref_no
 * @property int          $type_id
 * @property Money        $amount
 * @property int          $currency_id
 * @property string       $debit_account_id
 * @property Money        $debit_beg_balance
 * @property Money        $debit_end_balance
 * @property string       $credit_account_id
 * @property Money        $credit_beg_balance
 * @property Money        $credit_end_balance
 * @property Wallet       $creditWallet
 * @property Wallet       $debitWallet
 * @property int          $status_id
 * @property Carbon       $created_at
 * @property Carbon       $updated_at

 */
class JournalTransaction extends AbstractModel
{
    public const DEFAULT_CURRENCY_ID = 115;
    public const DEFAULT_STATUS_ID = 3;
    public const TRANSACTION_TYPE_ID = 200;

    protected $table = 'journal_transaction';

    protected $fillable = [
        'amount',
        'transaction_id',
        'transaction_ref_no',
        'currency_id',
        'debit_account_id',
        'debit_beg_balance',
        'debit_end_balance',
        'credit_account_id',
        'credit_beg_balance',
        'credit_end_balance',
        'status_id'
    ];

    protected $casts = [
        'int'                => 'int',
        'uuid'               => 'string',
        'transaction_id'     => 'int',
        'transaction_ref_no' => 'string',
        'type_id'            => 'int',
        'currency_id'        => 'int',
        'debit_account_id'   => 'string',
        'credit_account_id'  => 'string',
        'status_id'          => 'int',
        'created_at'         => 'datetime',
        'updated_at'         => 'datetime',
    ];

    /** @inheritDoc */
    public static function boot(): void
    {
        parent::boot();

        static::creating(static function (JournalTransaction $transaction) {
            $transaction->uuid = Str::orderedUuid()->toString();
            $transaction->type_id = static::TRANSACTION_TYPE_ID;
            $transaction->status_id = static::DEFAULT_STATUS_ID;
            $transaction->currency_id = static::DEFAULT_CURRENCY_ID;
        });
    }

    /**
     * Relationship between the User and their main Wallet record
     *
     * @return HasOne
     */
    public function debitWallet(): HasOne
    {
        return $this->hasOne(Wallet::class, 'ACCOUNT_ID', 'debit_account_id');
    }

    /**
     * Relationship between the User and their main Wallet record
     *
     * @return HasOne
     */
    public function creditWallet(): HasOne
    {
        return $this->hasOne(Wallet::class, 'ACCOUNT_ID', 'credit_account_id');
    }
}
